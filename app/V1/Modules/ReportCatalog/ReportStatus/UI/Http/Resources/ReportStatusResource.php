<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\UI\Http\Resources;

use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;

class ReportStatusResource extends ApiResponseResource
{
    public function getResourceData(): array
    {
        return [
            'slug' => $this->getResource()->slug,
            'name' => $this->getResource()->name,
            'description' => $this->getResource()->description,
        ];
    }

    public function getResource(): ReportStatus
    {
        /** @var ReportStatus $resource */
        $resource = $this->resource;

        return $resource;
    }

    public function getRelationships(): array
    {
        return [];
    }

    protected function shouldIncludeId(): bool
    {
        return false;
    }
}
