<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\UI\Http\Resources;

use App\V1\Shared\UI\Http\Resources\ApiCollectionResource;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;

class CollectionPlatformResource extends ApiCollectionResource
{
    public function getResource(mixed $model): ApiResponseResource
    {
        return new PlatformResource(
            $model,
            $this->relationships,
        );
    }
}
