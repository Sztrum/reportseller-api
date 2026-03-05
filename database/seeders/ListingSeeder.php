<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

final class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $platformIds = Platform::query()->pluck('id')->values();
        $categoryIds = ReportCategory::query()->pluck('id')->values();

        Listing::factory()
            ->count(100)
            ->sequence(static fn (Sequence $sequence): array => [
                'platform_id' => $platformIds[$sequence->index % $platformIds->count()],
                'report_category_id' => $categoryIds[$sequence->index % $categoryIds->count()],
            ])
            ->create();
    }
}
