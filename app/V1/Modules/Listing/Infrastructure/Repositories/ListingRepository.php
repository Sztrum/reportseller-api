<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Listing\Domain\Models\Listing;

class ListingRepository extends EloquentModelRepository
{
    public function model(): Listing
    {
        return new Listing();
    }

    public function moduleName(): string
    {
        return 'listing';
    }

    public function firstByExternalId(
        string $externalId
    ): Listing {
        return $this->query()
            ->where('external_id', $externalId)
            ->first();
    }
}
