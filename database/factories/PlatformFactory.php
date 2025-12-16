<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlatformFactory extends Factory
{
    public function definition(): array
    {
        $slug = $this->faker->unique()->slug(2);

        return [
            'name' => Str::title(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'base_url' => $this->faker->unique()->realText(255, true),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
