<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\Platform\Domain\Models\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlatformFactory extends Factory
{
    protected $model = Platform::class;

    public function definition(): array
    {
        $slug = $this->faker->unique()->slug(2);

        return [
            'name' => Str::title(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'base_url' => 'https://' . $slug . '.test',
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
