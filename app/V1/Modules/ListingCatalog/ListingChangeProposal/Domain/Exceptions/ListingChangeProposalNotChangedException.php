<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ListingChangeProposalNotChangedException extends DomainException
{
    /**
     * @see app/V1/Modules/ListingCatalog/ListingChangeProposal/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'listing-change-proposal::exceptions.other.listing_change_proposal_not_changed';

    public function __construct(
        ?string $message = null,
        array $replace = [],
    ) {
        parent::__construct($message ?? self::resolveDefaultMessage(self::DEFAULT_MESSAGE_KEY, $replace), $replace);
    }
}
