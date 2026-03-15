<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingHistory\Domain\Models\ListingHistory;
use Database\Factories\ListingHistoryFactory;
use Illuminate\Database\Seeder;
use Random\RandomException;
use RuntimeException;

final class ListingHistorySeeder extends Seeder
{
    /**
     * @throws RandomException
     */
    public function run(): void
    {
        $listings = Listing::query()->inRandomOrder()->get();

        if ($listings->isEmpty()) {
            throw new RuntimeException('ListingHistorySeeder requires seeded listings.');
        }

        $listings->each(function (Listing $listing): void {
            $historyCount = random_int(1, 3);
            $capturedAt = now()->subDays(random_int(5, 45));
            /** @var ListingHistoryFactory $listingHistoryFactory */
            $listingHistoryFactory = ListingHistory::factory();

            for ($index = 0; $index < $historyCount; $index++) {
                $listingHistoryFactory->create([
                    'listing_id' => $listing->id,
                    'external_id' => $listing->external_id,
                    'url' => $listing->url,
                    'title' => fake()->sentence(4),
                    'description' => fake()->paragraph(),
                    'price' => $listing->price === null
                        ? random_int(500, 20_000)
                        : max(1, $listing->price + random_int(-300, 300)),
                    'currency' => $listing->currency ?? 'PLN',
                    'seller_type' => $listing->seller_type !== null ? $listing->seller_type->value : fake()->randomElement(ListingSellerTypeEnum::values()),
                    'seller_name' => $listing->seller_name ?? fake()->name(),
                    'location' => $listing->location ?? fake()->city(),
                    'thumbnail_url' => $listing->thumbnail_url ?? fake()->imageUrl(),
                    'is_active_external' => fake()->boolean(),
                    'captured_at' => (clone $capturedAt)->addHours($index),
                ]);
            }
        });
    }
}
