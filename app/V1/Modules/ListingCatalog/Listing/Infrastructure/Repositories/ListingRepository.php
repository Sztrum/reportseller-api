<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;

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
    ): ?Listing {
        /** @var Listing|null $listing */
        $listing = $this->query()
            ->where('external_id', $externalId)
            ->first();

        return $listing;
    }

    public function firstByExternalIdAndPlatform(
        string $externalId,
        string $platformId
    ): ?Listing {
        /** @var Listing|null $listing */
        $listing = $this->query()
            ->where('external_id', $externalId)
            ->where('platform_id', $platformId)
            ->first();

        return $listing;
    }

    public function firstByUrl(
        string $url
    ): ?Listing {
        /** @var Listing|null $listing */
        $listing = $this->query()
            ->where('url', $url)
            ->first();

        return $listing;
    }
}
