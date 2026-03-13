<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
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
            'report_id' => $receiptData['report_id'],
            'receipt_token' => $receiptData['receipt_token'],
        ]))
            ->assertOk()
            ->assertJson([
                'id' => $report->id,
                'title' => 'Podejrzane ogloszenie',
                'description' => 'Opis ma wiecej niz dziesiec znakow i jest poprawny.',
                'is_published' => false,
                'listing' => [
                    'id' => $listing->id,
                    'url' => 'https://olx.pl/oferta/123',
                    'title' => 'Tytul listingu',
                    'price' => 1200,
                    'currency' => 'PLN',
                    'platform_slug' => 'olx',
                ],
                'report_status' => [
                    'slug' => 'new',
                ],
            ]);
    }

    public function test_it_denies_receipt_access_when_token_is_invalid(): void
    {
        $this->seedStoreDependencies();

        $storeResponse = $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/private-receipt']
        ));

        $receiptData = $this->assertReceiptResponse($storeResponse);

        $this->getJson(route('api.reports.receipt.show', [
            'report_id' => $receiptData['report_id'],
            'receipt_token' => 'invalid-token',
        ]))
            ->assertForbidden()
            ->assertJson([
                'status' => 403,
                'message' => 'Brak dostepu do potwierdzenia tego zgloszenia.',
            ]);
    }

    public function test_it_requires_receipt_token_for_receipt_access(): void
    {
        $this->seedStoreDependencies();

        $storeResponse = $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: ['url' => 'https://olx.pl/oferta/receipt-required']
        ));

        $receiptData = $this->assertReceiptResponse($storeResponse);

        $this->getJson(route('api.reports.receipt.show', [
            'report_id' => $receiptData['report_id'],
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['receipt_token']);
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
     * @return array{report_id: string, receipt_url: string, receipt_token: string}
     */
    private function assertReceiptResponse(TestResponse $response): array
    {
        $response
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'receipt_url',
                'receipt_token',
            ])
            ->assertJson([
                'message' => 'Zgloszenie zostalo zapisane.',
            ]);

        $json = $response->json();

        $this->assertIsArray($json);
        $this->assertIsString($json['receipt_url'] ?? null);
        $this->assertIsString($json['receipt_token'] ?? null);
        $this->assertNotSame('', $json['receipt_token']);

        $receiptPath = parse_url((string) $json['receipt_url'], PHP_URL_PATH);

        $this->assertIsString($receiptPath);

        $receiptPathSegments = explode('/', trim($receiptPath, '/'));
        $reportId = $receiptPathSegments[count($receiptPathSegments) - 2] ?? null;

        $this->assertIsString($reportId);
        $this->assertSame(route('api.reports.receipt.show', ['report_id' => $reportId]), $json['receipt_url']);

        return [
            'report_id' => $reportId,
            'receipt_url' => (string) $json['receipt_url'],
            'receipt_token' => (string) $json['receipt_token'],
        ];
    }
}
