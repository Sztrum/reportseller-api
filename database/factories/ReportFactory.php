<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class ReportFactory extends Factory
{
    public function definition(): array
    {

        return [
            'listing_id' => $this->faker->uuid(),
            'status_id' => $this->faker->uuid(),
            'reporter_email' => $this->faker->optional()->safeEmail(),
            'description' => $this->faker->paragraphs(2, true),
            'moderation_note' => null,
            'is_published' => false,
            'published_at' => null,
        ];
    }

    public function published(): self
    {
        return $this->state([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
