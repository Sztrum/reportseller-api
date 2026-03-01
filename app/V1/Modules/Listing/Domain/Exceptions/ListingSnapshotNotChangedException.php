<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ListingSnapshotNotChangedException extends DomainException
{
    /**
     * @see app/V1/Modules/Listing/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'listing::exceptions.other.snapshot_not_changed';

    public function __construct(
        ?string $message = null,
        array $replace = [],
    ) {
        parent::__construct($message ?? __(self::DEFAULT_MESSAGE_KEY, $replace), $replace);
    }
}
