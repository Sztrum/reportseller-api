<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'listing_id' => $this->faker->uuid(),
            'report_status_id' => $this->faker->uuid(),
            'report_author_id' => $this->faker->uuid(),
            'description' => $this->faker->paragraphs(2, true),
            'moderation_note' => null,
            'is_published' => false,
            'published_at' => null,
            'deleted_at' => null,
        ];
    }

    public function published(): self
    {
        return $this->state([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function deleted(): self
    {
        return $this->state([
            'deleted_at' => now(),
        ]);
    }
}
