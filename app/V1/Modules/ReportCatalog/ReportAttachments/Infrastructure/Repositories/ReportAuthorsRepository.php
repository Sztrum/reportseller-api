<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAttachments\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\ReportAttachments\Domain\Models\ReportAttachment;

class ReportAuthorsRepository extends EloquentModelRepository
{
    public function model(): ReportAttachment
    {
        return new ReportAttachment();
    }

    public function moduleName(): string
    {
        return 'report-attachment';
    }
}
