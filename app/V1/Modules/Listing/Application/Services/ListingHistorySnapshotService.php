<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Application\Services;

use App\V1\Modules\Listing\Domain\Aggregates\ListingHistoryAggregate;
use App\V1\Modules\Listing\Domain\DTO\ListingDTO;
use App\V1\Modules\Listing\Domain\Exceptions\ListingSnapshotNotChangedException;
use App\V1\Modules\Listing\Domain\Models\ListingHistory;
use App\V1\Modules\Listing\Infrastructure\Repositories\ListingHistoryRepository;

readonly class ListingHistorySnapshotService
{
    public function __construct(
        private ListingHistoryRepository $listingHistoryRepository,
        private ListingHistoryAggregate $listingHistoryAggregate,
    ) {
    }

    /**
     * @return array<string, mixed>|null
     * @throws \Throwable
     */
    public function getSnapshotIfChanged(
        string $listingId,
        ListingDTO $listingDTO,
    ): ?array {
        /** @var array<string, mixed> $snapshot */
        $snapshot = [
            'listing_id' => $listingId,
            'external_id' => $listingDTO->external_id,
            'url' => $listingDTO->url,
            'title' => $listingDTO->title,
            'description' => $listingDTO->description,
            'price' => $listingDTO->price,
            'currency' => $listingDTO->currency,
            'seller_type' => $listingDTO->seller_type,
            'seller_name' => $listingDTO->seller_name,
            'location' => $listingDTO->location,
            'thumbnail_url' => $listingDTO->thumbnail_url,
            'is_active_external' => $listingDTO->is_active_external,
            'captured_at' => now(),
        ];

        $lastSnapshot = $this->listingHistoryRepository->latestForListing($listingId);
        $hasChanges = $this->hasSnapshotChanges($lastSnapshot, $snapshot);

        try {
            $this->listingHistoryAggregate->ensureSnapshotHasChanges($hasChanges);
            return $snapshot;
        } catch (ListingSnapshotNotChangedException) {
            return null;
        }
    }

    /**
     * @param array<string, mixed> $snapshot
     */
    private function hasSnapshotChanges(
        ?ListingHistory $lastSnapshot,
        array $snapshot,
    ): bool {
        if ($lastSnapshot === null) {
            return true;
        }

        $fieldsToCompare = [
            'title',
            'description',
            'price',
            'seller_type',
            'seller_name',
            'location',
            'thumbnail_url',
            'is_active_external',
        ];

        foreach ($fieldsToCompare as $field) {
            if ($lastSnapshot->getAttribute($field) != $snapshot[$field]) {
                return true;
            }
        }

        return false;
    }
}
