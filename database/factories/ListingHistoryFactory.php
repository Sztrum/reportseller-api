<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingHistory\Domain\Models\ListingHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'external_id' => (string) Str::uuid(),
            'url' => 'https://example.test/listing-history/' . Str::uuid(),
            'title' => $this->faker->optional()->sentence(),
            'description' => $this->faker->optional()->paragraph(),
            'price' => $this->faker->optional()->numberBetween(1_000, 50_000_000),
            'currency' => 'PLN',
            'seller_type' => $this->faker->optional()->randomElement(['private', 'business']),
            'seller_name' => $this->faker->optional()->name(),
            'location' => $this->faker->optional()->city(),
            'thumbnail_url' => $this->faker->optional()->url(),
            'is_active_external' => $this->faker->boolean(),
            'captured_at' => now(),
        ];
    }

    public function capturedRecently(): self
    {
        return $this->state([
            'captured_at' => now()->subMinutes(15),
        ]);
    }

    public function externalInactive(): self
    {
        return $this->state([
            'is_active_external' => false,
        ]);
    }
}
