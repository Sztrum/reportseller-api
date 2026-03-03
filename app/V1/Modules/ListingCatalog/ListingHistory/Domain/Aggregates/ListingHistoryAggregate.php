<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingHistory\Domain\Aggregates;

use App\V1\Modules\ListingCatalog\ListingHistory\Domain\Exceptions\ListingSnapshotNotChangedException;
use Throwable;

readonly class ListingHistoryAggregate
{
    /**
     * @throws ListingSnapshotNotChangedException|Throwable
     */
    public function ensureSnapshotHasChanges(
        bool $hasChanges,
        string $exception = ListingSnapshotNotChangedException::class,
    ): void {
        throw_if(!$hasChanges, $exception);
    }
}
