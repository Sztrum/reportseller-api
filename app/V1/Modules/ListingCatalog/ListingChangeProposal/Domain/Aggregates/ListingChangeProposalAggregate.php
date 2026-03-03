<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Aggregates;

use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Exceptions\ListingChangeProposalDuplicatePendingException;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Exceptions\ListingChangeProposalNotChangedException;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;
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

    /**
     * @param array<string, mixed> $proposedPayload
     * @throws ListingChangeProposalDuplicatePendingException|Throwable
     */
    public function ensureNoDuplicatePendingProposal(
        ?ListingChangeProposal $latestPendingProposal,
        array $proposedPayload,
        string $exception = ListingChangeProposalDuplicatePendingException::class,
    ): void {
        throw_if(
            $latestPendingProposal !== null && $latestPendingProposal->proposed_payload === $proposedPayload,
            $exception
        );
    }

}
