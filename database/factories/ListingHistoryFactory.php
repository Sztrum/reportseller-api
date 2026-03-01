<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\Listing\Domain\Models\ListingHistory;
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
            'listing_id' => $this->faker->uuid(),
            'external_id' => $this->faker->optional()->uuid(),
            'url' => $this->faker->url(),
            'title' => $this->faker->optional()->sentence(),
            'description' => $this->faker->optional()->paragraph(),
            'price' => $this->faker->optional()->numberBetween(1_000, 50_000_000),
            'currency' => $this->faker->optional()->currencyCode(),
            'seller_type' => $this->faker->optional()->randomElement(['private', 'business']),
            'seller_name' => $this->faker->optional()->name(),
            'location' => $this->faker->optional()->city(),
            'thumbnail_url' => $this->faker->optional()->url(),
            'is_active_external' => $this->faker->boolean(),
            'captured_at' => now(),
        ];
    }
}
