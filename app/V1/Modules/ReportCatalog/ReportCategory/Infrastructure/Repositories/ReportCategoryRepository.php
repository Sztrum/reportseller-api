<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportStatus;

class ReportCategoryRepository extends EloquentModelRepository
{
    public function model(): ReportStatus
    {
        return new ReportStatus();
    }

    public function moduleName(): string
    {
        return 'report-category';
    }
}
