<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingHistory\Domain\Models\ListingHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ListingHistory>
 */
final class ListingHistoryFactory extends Factory
{
    /** @var class-string<ListingHistory> */
    protected $model = ListingHistory::class;

    public function definition(): array
    {
        return [
            'listing_id' => Listing::factory(),
            'external_id' => $this->faker->uuid(),
            'url' => $this->faker->unique()->url(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(500, 15_000),
            'currency' => 'PLN',
            'seller_type' => $this->faker->randomElement(ListingSellerTypeEnum::cases()),
            'seller_name' => $this->faker->name(),
            'location' => $this->faker->city(),
            'thumbnail_url' => $this->faker->imageUrl(),
            'is_active_external' => $this->faker->boolean(),
            'captured_at' => $this->faker->dateTimeBetween('-60 days', '-1 day'),
        ];
    }
}
