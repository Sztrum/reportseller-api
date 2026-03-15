<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

final class ListingSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Collection<int, string> $platformIds */
        $platformIds = Platform::query()->pluck('id')->values();
        /** @var Collection<int, string> $categoryIds */
        $categoryIds = ReportCategory::query()->pluck('id')->values();

        /** @var ListingFactory $listingFactory */
        $listingFactory = Listing::factory();

        $listingFactory
            ->count(100)
            ->sequence(static fn (Sequence $sequence): array => [
                'platform_id' => $platformIds[$sequence->index % $platformIds->count()],
                'report_category_id' => $categoryIds[$sequence->index % $categoryIds->count()],
            ])
            ->create();
    }
}
