<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Domain\Aggregates;

use App\V1\Modules\Listing\Domain\Exceptions\ListingAlreadyExistsException;
use App\V1\Modules\Listing\Domain\Models\Listing;
use Throwable;

readonly class ListingAggregate
{
    /**
     * @throws ListingAlreadyExistsException|Throwable
     */
    public function ensureListingNotExists(
        ?Listing $listing,
        string $exception = ListingAlreadyExistsException::class,
    ): void {
        throw_if($listing !== null, $exception);
    }
}
