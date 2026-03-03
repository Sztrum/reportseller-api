<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;

class ListingChangeProposalRepository extends EloquentModelRepository
{
    public function model(): ListingChangeProposal
    {
        return new ListingChangeProposal();
    }

    public function moduleName(): string
    {
        return 'listing-change-proposal';
    }

    public function latestPendingForListing(
        string $listingId
    ): ?ListingChangeProposal {
        /** @var ListingChangeProposal|null $proposal */
        $proposal = $this->query()
            ->where('listing_id', $listingId)
            ->where('status', ListingChangeProposal::STATUS_PENDING)
            ->orderByDesc('requested_at')
            ->orderByDesc('created_at')
            ->first();

        return $proposal;
    }
}
