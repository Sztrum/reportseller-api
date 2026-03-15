<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\UI\Http\Resources;

use App\V1\Shared\UI\Http\Resources\ApiCollectionResource;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;

class CollectionReportCategoryResource extends ApiCollectionResource
{
    public function getResource(mixed $model): ApiResponseResource
    {
        return new ReportCategoryResource(
            $model,
            $this->relationships,
        );
    }
}
