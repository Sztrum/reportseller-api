<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ListingAlreadyExistsException extends DomainException
{
    /**
     * @see app/V1/Modules/Listing/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'listing::exceptions.other.listing_already_exists';

    public function __construct(
        ?string $message = null,
        array $replace = [],
    ) {
        parent::__construct($message ?? __(self::DEFAULT_MESSAGE_KEY, $replace), $replace);
    }
}
