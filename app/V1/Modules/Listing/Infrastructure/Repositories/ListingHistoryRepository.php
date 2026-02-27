<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Listing\Domain\Models\ListingHistory;

class ListingHistoryRepository extends EloquentModelRepository
{
    public function model(): ListingHistory
    {
        return new ListingHistory();
    }

    public function moduleName(): string
    {
        return 'listing-history';
    }

    public function latestForListing(
        string $listingId
    ): ?ListingHistory {
        /** @var ListingHistory|null $listingHistory */
        $listingHistory = $this->query()
            ->where('listing_id', $listingId)
            ->orderByDesc('captured_at')
            ->orderByDesc('id')
            ->first();

        return $listingHistory;
    }
}
