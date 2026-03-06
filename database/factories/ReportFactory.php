<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models\ReportAuthor;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
final class ReportFactory extends Factory
{
    /** @var class-string<Report> */
    protected $model = Report::class;

    public function definition(): array
    {
        $published = $this->faker->boolean(30);

        return [
            'listing_id' => Listing::factory(),
            'report_status_id' => ReportStatus::factory(),
            'report_author_id' => ReportAuthor::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'moderation_note' => $this->faker->boolean(55) ? $this->faker->sentence() : null,
            'is_published' => $published,
            'published_at' => $published ? $this->faker->dateTimeBetween('-14 days') : null,
        ];
    }
}
