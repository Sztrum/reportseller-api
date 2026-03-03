<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;
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

        $this->assertDatabaseCount('listing_histories', 0);
        $this->assertDatabaseCount('listing_change_proposals', 0);
        $this->assertDatabaseHas('reports', [
            'listing_id' => $listing->id,
            'title' => 'Podejrzane ogloszenie',
            'description' => 'Opis ma wiecej niz dziesiec znakow i jest poprawny.',
        ]);
        $this->assertDatabaseCount('report_authors', 1);
        $this->assertDatabaseCount('report_attachments', 1);
    }

    public function test_it_does_not_create_change_proposal_when_listing_data_is_unchanged(): void
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
        $this->assertDatabaseCount('listing_histories', 0);
        $this->assertDatabaseCount('listing_change_proposals', 0);
        $this->assertDatabaseCount('reports', 2);
    }

    public function test_it_creates_pending_change_proposal_when_existing_listing_data_changes(): void
    {
        $this->seedStoreDependencies();

        $firstPayload = $this->buildPayload(
            listingOverrides: [
                'url' => 'https://olx.pl/oferta/777',
                'price' => 1200,
                'title' => 'Pierwszy tytul',
            ]
        );

        $this->postJson(route('api.reports.store'), $firstPayload)->assertOk();

        $secondPayload = $this->buildPayload(
            listingOverrides: [
                'url' => 'https://olx.pl/oferta/777',
                'price' => 1500,
                'title' => 'Nowszy tytul',
            ],
            reportOverrides: [
                'title' => 'Drugie zgloszenie',
                'description' => 'Drugie zgloszenie z aktualizacja danych listingu.',
            ]
        );

        $this->postJson(route('api.reports.store'), $secondPayload)->assertOk();

        $listing = Listing::query()->first();
        $this->assertNotNull($listing);

        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'price' => '1200',
            'title' => 'Pierwszy tytul',
        ]);

        /** @var ListingChangeProposal|null $changeProposal */
        $changeProposal = ListingChangeProposal::query()->first();
        $this->assertNotNull($changeProposal);
        $this->assertSame(ListingChangeProposal::STATUS_PENDING, $changeProposal->status);

        $this->assertSame(1200, $changeProposal->current_payload['price']);
        $this->assertSame('Pierwszy tytul', $changeProposal->current_payload['title']);
        $this->assertSame(1500, $changeProposal->proposed_payload['price']);
        $this->assertSame('Nowszy tytul', $changeProposal->proposed_payload['title']);
        $this->assertDatabaseCount('listing_histories', 0);
    }

    public function test_change_proposal_review_endpoint_is_not_available(): void
    {
        $this->seedStoreDependencies();

        $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: [
                'url' => 'https://olx.pl/oferta/888',
                'price' => 1100,
                'title' => 'Stary tytul',
            ]
        ))->assertOk();

        $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: [
                'url' => 'https://olx.pl/oferta/888',
                'price' => 1700,
                'title' => 'Nowy tytul',
            ],
            reportOverrides: [
                'title' => 'Zmiana danych',
                'description' => 'Prosze o aktualizacje danych listingu.',
            ]
        ))->assertOk();

        /** @var ListingChangeProposal|null $changeProposal */
        $changeProposal = ListingChangeProposal::query()->first();
        $this->assertNotNull($changeProposal);

        $this->postJson('/v1/listing-catalog-listing-change-proposals/' . $changeProposal->id . '/review', [
            'decision' => ListingChangeProposal::STATUS_APPROVED,
            'reviewed_by' => 'moderator@example.com',
            'review_reason' => 'Potwierdzono wiarygodnosc zmiany.',
        ])->assertNotFound();
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
