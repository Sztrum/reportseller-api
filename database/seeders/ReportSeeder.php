<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models\ReportAuthor;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Illuminate\Database\Seeder;
use Random\RandomException;
use RuntimeException;

final class ReportSeeder extends Seeder
{
    /**
     * @throws RandomException
     */
    public function run(): void
    {
        $listings = Listing::query()->inRandomOrder()->get();
        $statuses = ReportStatus::query()->get();

        if ($listings->isEmpty() || $statuses->isEmpty()) {
            throw new RuntimeException('ReportSeeder requires seeded listings and report statuses.');
        }

        foreach ($listings as $listing) {
            $reportsPerListing = random_int(1, 2);

            for ($index = 0; $index < $reportsPerListing; $index++) {
                $author = ReportAuthor::factory()->create([
                    'ip_hash' => hash('sha256', fake()->ipv4()),
                    'consent_version' => 'v1',
                    'consent_given_at' => fake()->dateTimeBetween('-6 months'),
                    'is_blocked' => false,
                    'blocked_at' => null,
                ]);

                Report::factory()->create([
                    'listing_id' => $listing->id,
                    'report_status_id' => $statuses->random()->id,
                    'report_author_id' => $author->id,
                    'title' => fake()->sentence(4),
                    'description' => sprintf(
                        'Zgloszenie dotyczace oferty "%s" (%s). %s',
                        $listing->title,
                        $listing->url,
                        fake()->paragraph()
                    ),
                    'moderation_note' => fake()->boolean(55) ? fake()->sentence() : null,
                    'is_published' => fake()->boolean(30),
                    'published_at' => fake()->boolean(30) ? now()->subDays(random_int(0, 5)) : null,
                ]);
            }
        }
    }
}
