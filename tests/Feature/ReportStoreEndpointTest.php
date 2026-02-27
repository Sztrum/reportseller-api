<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\Listing\Domain\Models\Listing;
use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportStoreEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_report_with_related_entities_and_title(): void
    {
        $this->seedStoreDependencies();

        $payload = $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/123'],
            reportOverrides: [
                'title' => 'Podejrzane ogloszenie',
                'description' => 'Opis ma wiecej niz dziesiec znakow i jest poprawny.',
            ],
            attachments: [[
                'storage_key' => 'reports/2026/02/file-1.jpg',
                'storage_disk' => 's3',
                'original_name' => 'file-1.jpg',
                'mime_type' => 'image/jpeg',
                'size_bytes' => 2048,
            ]]
        );

        $response = $this->postJson(route('api.reports.store'), $payload);

        $response->assertOk();

        $listing = Listing::query()->first();
        $this->assertNotNull($listing);

        $this->assertDatabaseCount('listing_histories', 1);
        $this->assertDatabaseHas('reports', [
            'listing_id' => $listing->id,
            'title' => 'Podejrzane ogloszenie',
            'description' => 'Opis ma wiecej niz dziesiec znakow i jest poprawny.',
        ]);
        $this->assertDatabaseCount('report_authors', 1);
        $this->assertDatabaseCount('report_attachments', 1);
    }

    public function test_it_does_not_create_new_listing_history_when_listing_data_is_unchanged(): void
    {
        $this->seedStoreDependencies();

        $basePayload = $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/555']
        );

        $this->postJson(route('api.reports.store'), $basePayload)->assertOk();

        $secondPayload = $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/555'],
            reportOverrides: [
                'title' => 'Drugie zgloszenie',
                'description' => 'Drugie zgloszenie tego samego ogloszenia bez zmiany danych listingu.',
            ]
        );

        $this->postJson(route('api.reports.store'), $secondPayload)->assertOk();

        $this->assertDatabaseCount('listings', 1);
        $this->assertDatabaseCount('listing_histories', 1);
        $this->assertDatabaseCount('reports', 2);
    }

    private function seedStoreDependencies(): void
    {
        Platform::factory()->create([
            'slug' => 'olx',
            'name' => 'OLX',
            'base_url' => 'https://olx.pl',
            'is_active' => true,
        ]);

        ReportCategory::factory()->create([
            'slug' => 'fraud',
            'name' => 'Fraud',
            'is_active' => true,
        ]);

        ReportStatus::factory()->create([
            'slug' => 'new',
            'name' => 'New',
            'is_active' => true,
        ]);
    }

    /**
     * @param array<string, mixed> $listingOverrides
     * @param array<string, mixed> $reportOverrides
     * @param array<int, array<string, mixed>> $attachments
     * @return array<string, mixed>
     */
    private function buildPayload(
        array $listingOverrides = [],
        array $reportOverrides = [],
        array $attachments = [],
    ): array {
        $listing = array_merge([
            'url' => 'https://olx.pl/oferta/default',
            'platform_slug' => 'olx',
            'report_category_slug' => 'fraud',
            'external_id' => 'ext-1',
            'title' => 'Tytul listingu',
            'description' => 'Opis listingu dluzszy niz dziesiec znakow.',
            'price' => 1200,
            'currency' => 'PLN',
            'seller_type' => 'private',
            'seller_name' => 'Jan Kowalski',
            'location' => 'Warszawa',
            'thumbnail_url' => 'https://example.com/thumb.jpg',
            'is_active_external' => true,
        ], $listingOverrides);

        $report = array_merge([
            'title' => 'Zgloszenie ogloszenia',
            'description' => 'To zgloszenie zawiera poprawny i wystarczajaco dlugi opis.',
        ], $reportOverrides);

        return [
            'listing' => $listing,
            'report' => $report,
            'author' => [
                'consent_version' => 'v1',
                'consent_given_at' => now()->toISOString(),
            ],
            'attachments' => $attachments,
        ];
    }
}
