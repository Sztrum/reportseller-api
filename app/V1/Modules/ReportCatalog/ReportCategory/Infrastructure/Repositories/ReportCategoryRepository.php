<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;

class ReportCategoryRepository extends EloquentModelRepository
{
    public function model(): ReportCategory
    {
        return new ReportCategory();
    }

    public function moduleName(): string
    {
        return 'report-category';
    }

    public function firstBySlug(
        string $slug
    ): ?ReportCategory {
        /** @var ReportCategory|null $reportCategory */
        $reportCategory = $this->query()
            ->where('slug', $slug)
            ->first();

        return $reportCategory;
    }
}
