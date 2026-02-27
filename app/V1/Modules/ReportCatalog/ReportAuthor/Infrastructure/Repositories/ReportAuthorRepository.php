<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAuthor\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models\ReportAuthor;

class ReportAuthorRepository extends EloquentModelRepository
{
    public function model(): ReportAuthor
    {
        return new ReportAuthor();
    }

    public function moduleName(): string
    {
        return 'report-author';
    }
}
