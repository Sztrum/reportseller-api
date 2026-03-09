<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Database\Factories\ListingChangeProposalFactory;
use Illuminate\Database\Seeder;
use Random\RandomException;
use RuntimeException;

final class ListingChangeProposalSeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::query()->inRandomOrder()->get();

        if ($reports->isEmpty()) {
            throw new RuntimeException('ListingChangeProposalSeeder requires seeded reports.');
        }

        $reports->each(/**
         * @throws RandomException
         */ function (Report $report): void {
            /** @var Listing $listing */
            $listing = Listing::query()->findOrFail($report->listing_id);
            /** @var ListingChangeProposalFactory $listingChangeProposalFactory */
            $listingChangeProposalFactory = ListingChangeProposal::factory();

            $listingChangeProposalFactory->create([
                'listing_id' => $listing->id,
                'current_payload' => [
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
                ],
                'proposed_payload' => [
                    'platform_id' => $listing->platform_id,
                    'report_category_id' => $listing->report_category_id,
                    'external_id' => $listing->external_id,
                    'url' => fake()->url(),
                    'title' => fake()->sentence(4),
                    'description' => fake()->paragraph(),
                    'price' => ($listing->price ?? random_int(500, 10_000)) + random_int(50, 900),
                    'currency' => $listing->currency ?? 'PLN',
                    'seller_type' => $listing->seller_type !== null ? $listing->seller_type->value : fake()->randomElement(ListingSellerTypeEnum::values()),
                    'seller_name' => fake()->name(),
                    'location' => fake()->city(),
                    'thumbnail_url' => fake()->imageUrl(),
                    'is_active_external' => fake()->boolean(),
                ],
            ]);
        });
    }
}
