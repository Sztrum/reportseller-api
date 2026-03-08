<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Database\Seeders\PlatformSeeder;
use Database\Seeders\ReportCategorySeeder;
use Database\Seeders\ReportStatusSeeder;
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
        $this->assertDatabaseCount('report_attachments', 0);
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

    public function test_it_falls_back_to_existing_listing_lookup_by_url_when_external_id_changes(): void
    {
        $this->seedStoreDependencies();

        $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: [
                'external_id' => 'ext-1',
                'url' => 'https://olx.pl/oferta/fallback-url',
            ]
        ))->assertOk();

        $response = $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: [
                'external_id' => 'ext-2',
                'url' => 'https://olx.pl/oferta/fallback-url',
            ],
            reportOverrides: [
                'title' => 'Raport z nowym external id',
                'description' => 'Drugie zgloszenie powinno trafic do tego samego listingu po URL.',
            ]
        ));

        $response->assertOk();

        $this->assertDatabaseCount('listings', 1);
        $this->assertDatabaseCount('reports', 2);
        $this->assertDatabaseHas('listings', [
            'external_id' => 'ext-1',
            'url' => 'https://olx.pl/oferta/fallback-url',
        ]);
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

        $this->assertEquals($this->expectedCurrentPayload($listing), $changeProposal->current_payload);
        $this->assertEquals($this->expectedProposalPayload([
            'url' => 'https://olx.pl/oferta/777',
            'price' => 1500,
            'title' => 'Nowszy tytul',
        ], $listing), $changeProposal->proposed_payload);
        $this->assertDatabaseCount('listing_histories', 0);
    }

    public function test_it_does_not_create_duplicate_pending_change_proposal_for_identical_payload(): void
    {
        $this->seedStoreDependencies();

        $this->postJson(route('api.reports.store'), $this->buildPayload(
            listingOverrides: [
                'url' => 'https://olx.pl/oferta/4321',
                'price' => 1000,
                'title' => 'Tytul bazowy',
            ]
        ))->assertOk();

        $changedPayload = $this->buildPayload(
            listingOverrides: [
                'url' => 'https://olx.pl/oferta/4321',
                'price' => 1400,
                'title' => 'Tytul po zmianie',
            ],
            reportOverrides: [
                'title' => 'Pierwsza propozycja zmiany',
                'description' => 'Pierwszy raport z ta sama zmiana danych listingu.',
            ]
        );

        $this->postJson(route('api.reports.store'), $changedPayload)->assertOk();
        $this->postJson(route('api.reports.store'), $changedPayload)->assertOk();

        $this->assertDatabaseCount('listing_change_proposals', 1);

        /** @var ListingChangeProposal|null $changeProposal */
        $changeProposal = ListingChangeProposal::query()->first();
        $this->assertNotNull($changeProposal);
        $this->assertSame(ListingChangeProposal::STATUS_PENDING, $changeProposal->status);
        $listing = Listing::query()->first();
        $this->assertNotNull($listing);
        $this->assertEquals($this->expectedProposalPayload([
            'url' => 'https://olx.pl/oferta/4321',
            'price' => 1400,
            'title' => 'Tytul po zmianie',
        ], $listing), $changeProposal->proposed_payload);
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

    public function test_it_returns_domain_error_when_default_new_report_status_is_missing(): void
    {
        $this->seed([
            PlatformSeeder::class,
            ReportCategorySeeder::class,
        ]);

        ReportStatus::query()->delete();

        $response = $this->postJson(route('api.reports.store'), $this->buildPayload());

        $response
            ->assertStatus(422)
            ->assertJson([
                'status' => 422,
                'message' => 'Status raportu o slug "new" nie zostal znaleziony.',
            ]);

        $this->assertDatabaseCount('reports', 0);
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
     * @return array<string, mixed>
     */
    private function buildPayload(
        array $listingOverrides = [],
        array $reportOverrides = [],
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

        return [
            'listing' => $listing,
            'report' => $report,
            'author' => [
                'consent_version' => 'v1',
                'consent_given_at' => now()->toISOString(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function expectedCurrentPayload(Listing $listing): array
    {
        return [
            'platform_id' => $listing->platform_id,
            'report_category_id' => $listing->report_category_id,
            'external_id' => $listing->external_id,
            'url' => $listing->url,
            'title' => $listing->title,
            'description' => $listing->description,
            'price' => $listing->price,
            'currency' => $listing->currency,
            'seller_type' => $listing->seller_type?->value,
            'seller_name' => $listing->seller_name,
            'location' => $listing->location,
            'thumbnail_url' => $listing->thumbnail_url,
            'is_active_external' => $listing->is_active_external,
        ];
    }

    /**
     * @param  array<string, mixed> $listingOverrides
     * @return array<string, mixed>
     */
    private function expectedProposalPayload(array $listingOverrides, Listing $listing): array
    {
        return array_merge($this->expectedCurrentPayload($listing), $listingOverrides);
    }
}
