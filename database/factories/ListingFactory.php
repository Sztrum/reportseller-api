<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\Listing\Domain\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        return [
            'platform_id' => $this->faker->uuid(),
            'report_category_id' => $this->faker->optional()->uuid(),
            'external_id' => null,
            'url' => 'https://example.test/listing/' . Str::uuid(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->optional()->paragraphs(3, true),
            'price' => $this->faker->optional()->numberBetween(1_000, 50_000_000),
            'currency' => 'PLN',
            'seller_type' => $this->faker->optional()->randomElement(['private', 'business']),
            'seller_name' => $this->faker->optional()->name(),
            'location' => $this->faker->optional()->city(),
            'thumbnail_url' => null,
            'is_active_external' => true,
            'is_active' => false,
        ];
    }
}
