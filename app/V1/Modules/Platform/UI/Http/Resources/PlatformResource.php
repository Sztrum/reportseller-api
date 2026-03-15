<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\UI\Http\Resources;

use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;

class PlatformResource extends ApiResponseResource
{
    public function getResourceData(): array
    {
        return [
            'name' => $this->getResource()->name,
            'slug' => $this->getResource()->slug,
            'base_url' => $this->getResource()->base_url,
        ];
    }

    public function getResource(): Platform
    {
        /** @var Platform $resource */
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
