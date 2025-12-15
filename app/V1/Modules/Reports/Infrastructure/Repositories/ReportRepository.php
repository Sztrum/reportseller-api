<?php

namespace App\V1\Modules\Reports\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Reports\Domain\Models\Report;

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

    public function firstById(
        string $id
    ): Report {
        return $this->query()->findOrFail($id);
    }
}
