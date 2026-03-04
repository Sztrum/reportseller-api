<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;
use App\V1\Modules\ListingCatalog\ListingHistory\Domain\Models\ListingHistory;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

final class ListingModerationSeeder extends Seeder
{
    public function run(): void
    {
        $report = Report::query()->first() ?? Report::factory()->create();
        $historyCapturedAt = Carbon::parse('2026-01-15 12:00:00');
        $pendingRequestedAt = Carbon::parse('2026-01-18 09:30:00');
        $approvedRequestedAt = Carbon::parse('2026-01-10 08:00:00');
        $approvedReviewedAt = Carbon::parse('2026-01-11 16:30:00');
        $rejectedRequestedAt = Carbon::parse('2026-01-09 10:00:00');
        $rejectedReviewedAt = Carbon::parse('2026-01-10 12:00:00');

        $pendingListing = Listing::query()->where('external_id', 'mod-1002')->first()
            ?? Listing::factory()->forModeration()->create(['external_id' => 'mod-1002']);
        $approvedListing = Listing::query()->where('external_id', 'mod-2001')->first()
            ?? Listing::factory()->inactive()->create(['external_id' => 'mod-2001']);
        $rejectedListing = Listing::query()->where('external_id', 'mod-2002')->first()
            ?? Listing::factory()->inactive()->create(['external_id' => 'mod-2002']);

        ListingHistory::query()->updateOrCreate(
            [
                'listing_id' => $pendingListing->id,
                'captured_at' => $historyCapturedAt,
            ],
            [
                'external_id' => $pendingListing->external_id,
                'url' => $pendingListing->url,
                'title' => $pendingListing->title,
                'description' => $pendingListing->description,
                'price' => $pendingListing->price,
                'currency' => $pendingListing->currency,
                'seller_type' => $pendingListing->seller_type,
                'seller_name' => $pendingListing->seller_name,
                'location' => $pendingListing->location,
                'thumbnail_url' => $pendingListing->thumbnail_url,
                'is_active_external' => $pendingListing->is_active_external,
            ],
        );

        ListingChangeProposal::query()->updateOrCreate(
            [
                'listing_id' => $pendingListing->id,
                'status' => ListingChangeProposal::STATUS_PENDING,
            ],
            [
                'report_id' => $report->id,
                'current_payload' => [
                    'title' => 'Rower - do moderacji',
                    'price' => 1450,
                    'currency' => 'PLN',
                ],
                'proposed_payload' => [
                    'title' => 'Rower gravel - wersja poprawiona',
                    'price' => 1650,
                    'currency' => 'PLN',
                ],
                'requested_at' => $pendingRequestedAt,
                'reviewed_at' => null,
                'reviewed_by' => null,
                'review_reason' => null,
            ],
        );

        ListingChangeProposal::query()->updateOrCreate(
            [
                'listing_id' => $approvedListing->id,
                'status' => ListingChangeProposal::STATUS_APPROVED,
            ],
            [
                'report_id' => $report->id,
                'current_payload' => [
                    'title' => 'Smartfon - stary tytul',
                    'price' => 900,
                    'currency' => 'PLN',
                ],
                'proposed_payload' => [
                    'title' => 'Smartfon - poprawiony tytul',
                    'price' => 850,
                    'currency' => 'PLN',
                ],
                'requested_at' => $approvedRequestedAt,
                'reviewed_at' => $approvedReviewedAt,
                'reviewed_by' => 'moderator@example.com',
                'review_reason' => 'Zmiana zgodna z dowodami.',
            ],
        );

        ListingChangeProposal::query()->updateOrCreate(
            [
                'listing_id' => $rejectedListing->id,
                'status' => ListingChangeProposal::STATUS_REJECTED,
            ],
            [
                'report_id' => $report->id,
                'current_payload' => [
                    'title' => 'Laptop - oferta testowa',
                    'price' => 3200,
                    'currency' => 'PLN',
                ],
                'proposed_payload' => [
                    'title' => 'Laptop premium - bez potwierdzenia',
                    'price' => 5100,
                    'currency' => 'PLN',
                ],
                'requested_at' => $rejectedRequestedAt,
                'reviewed_at' => $rejectedReviewedAt,
                'reviewed_by' => 'moderator@example.com',
                'review_reason' => 'Brak potwierdzenia zmiany ceny.',
            ],
        );
    }
}
