<?php

namespace App\V1\Modules\Report\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Report\Domain\Models\Report;

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
}
