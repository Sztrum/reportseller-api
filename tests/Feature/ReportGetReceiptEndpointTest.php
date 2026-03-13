<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Database\Seeders\PlatformSeeder;
use Database\Seeders\ReportCategorySeeder;
use Database\Seeders\ReportStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ReportGetReceiptEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_report_and_listing_data_for_valid_receipt_token(): void
    {
        $this->seedStoreDependencies();

        $storeResponse = $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/123'],
            reportOverrides: [
                'title' => 'Podejrzane ogloszenie',
                'description' => 'Opis ma wiecej niz dziesiec znakow i jest poprawny.',
            ],
        ));

        $receiptData = $this->assertReceiptResponse($storeResponse);

        $listing = Listing::query()->first();
        $report = Report::query()->first();

        $this->assertNotNull($listing);
        $this->assertNotNull($report);

        $this->getJson(route('api.reports.receipt.show', [
            'receipt_token' => $receiptData['receipt_token'],
        ]))
            ->assertOk()
            ->assertJson([
                'title' => 'Podejrzane ogloszenie',
                'description' => 'Opis ma wiecej niz dziesiec znakow i jest poprawny.',
                'is_published' => false,
                'listing' => [
                    'url' => 'https://olx.pl/oferta/123',
                    'title' => 'Tytul listingu',
                    'price' => 1200,
                    'currency' => 'PLN',
                    'platform_slug' => 'olx',
                ],
                'report_status' => [
                    'slug' => 'new',
                ],
            ])
            ->assertJsonMissingPath('id')
            ->assertJsonMissingPath('listing.id')
            ->assertJsonMissingPath('report_status.id')
            ->assertJsonMissingPath('moderation_note');
    }

    public function test_it_returns_not_found_when_token_is_invalid(): void
    {
        $this->seedStoreDependencies();

        $storeResponse = $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/private-receipt']
        ));

        $this->assertReceiptResponse($storeResponse);

        $this->getJson(route('api.reports.receipt.show', [
            'receipt_token' => 'invalid-token',
        ]))
            ->assertNotFound()
            ->assertJson([
                'status' => 404,
            ]);
    }

    public function test_it_returns_not_found_when_report_was_already_confirmed(): void
    {
        $this->seedStoreDependencies();

        $storeResponse = $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/confirmed-receipt']
        ));

        $receiptData = $this->assertReceiptResponse($storeResponse);

        $confirmedStatus = ReportStatus::query()->where('slug', 'confirmed')->first();

        $this->assertNotNull($confirmedStatus);

        $report = Report::query()->first();
        $this->assertNotNull($report);

        $report->forceFill([
            'report_status_id' => $confirmedStatus->id,
        ])->save();

        $this->getJson(route('api.reports.receipt.show', [
            'receipt_token' => $receiptData['receipt_token'],
        ]))
            ->assertNotFound();
    }

    private function seedStoreDependencies(): void
    {
        $this->seed([
            PlatformSeeder::class,
            ReportCategorySeeder::class,
            ReportStatusSeeder::class,
        ]);
    }

    /**
     * @param array<string, mixed> $listingOverrides
     * @param array<string, mixed> $reportOverrides
     * @param array<string, mixed> $authorOverrides
     * @return array<string, mixed>
     */
    private function buildPayload(
        array $listingOverrides = [],
        array $reportOverrides = [],
        array $authorOverrides = [],
    ): array {
        $listing = array_merge([
            'url' => 'https://olx.pl/oferta/default',
            'platform_slug' => 'olx',
            'report_category_slug' => 'ogolne',
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

        $author = array_merge([
            'email' => null,
            'consent_version' => 'v1',
            'consent_given_at' => now()->toISOString(),
        ], $authorOverrides);

        return [
            'listing' => $listing,
            'report' => $report,
            'author' => $author,
        ];
    }

    /**
     * @return array{receipt_token: string}
     */
    private function assertReceiptResponse(TestResponse $response): array
    {
        $response
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'receipt_token',
            ])
            ->assertJson([
                'message' => 'Zgloszenie zostalo zapisane.',
            ]);

        $json = $response->json();

        $this->assertIsArray($json);
        $this->assertIsString($json['receipt_token'] ?? null);
        $this->assertNotSame('', $json['receipt_token']);

        return [
            'receipt_token' => (string) $json['receipt_token'],
        ];
    }
}
