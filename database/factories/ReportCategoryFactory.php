<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReportCategoryFactory extends Factory
{
    protected $model = ReportCategory::class;

    public function definition(): array
    {
        $slug = $this->faker->unique()->slug(2);

        return [
            'name' => Str::title(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'description' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
