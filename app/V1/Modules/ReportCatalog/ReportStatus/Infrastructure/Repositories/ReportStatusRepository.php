<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;

class ReportStatusRepository extends EloquentModelRepository
{
    public function model(): ReportStatus
    {
        return new ReportStatus();
    }

    public function moduleName(): string
    {
        return 'report-status';
    }

    public function firstBySlug(
        string $slug
    ): ?ReportStatus {
        /** @var ReportStatus|null $reportStatus */
        $reportStatus = $this->query()
            ->where('slug', $slug)
            ->first();

        return $reportStatus;
    }
}
