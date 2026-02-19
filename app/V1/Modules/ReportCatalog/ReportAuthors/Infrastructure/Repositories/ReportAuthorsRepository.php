<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAuthors\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\ReportAuthors\Domain\Models\ReportAuthor;

class ReportAuthorsRepository extends EloquentModelRepository
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
