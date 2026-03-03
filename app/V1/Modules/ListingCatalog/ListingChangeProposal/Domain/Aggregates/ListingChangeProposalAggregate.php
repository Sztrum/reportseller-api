<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Aggregates;

use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Exceptions\ListingChangeProposalNotChangedException;
use Throwable;

readonly class ListingChangeProposalAggregate
{
    /**
     * @throws ListingChangeProposalNotChangedException|Throwable
     */
    public function ensureProposalHasChanges(
        bool $shouldCreateProposal,
        string $exception = ListingChangeProposalNotChangedException::class,
    ): void {
        throw_if(
            !$shouldCreateProposal,
            $exception
        );
    }

}
