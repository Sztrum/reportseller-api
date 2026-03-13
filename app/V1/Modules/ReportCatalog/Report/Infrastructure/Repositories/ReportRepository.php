<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;

class ReportRepository extends EloquentModelRepository
{
    public function model(): Report
    {
        return new Report();
    }

    public function moduleName(): string
    {
        return 'report';
    }

    public function firstByIdWithReceiptRelations(string $id): ?Report
    {
        /** @var Report|null $report */
        $report = $this->query()
            ->with([
                'listing.platform',
                'reportStatus',
                'reportAuthor',
            ])
            ->find($id);

        return $report;
    }
}
