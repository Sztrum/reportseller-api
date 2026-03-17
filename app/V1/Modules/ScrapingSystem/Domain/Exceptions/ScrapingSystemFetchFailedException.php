<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ScrapingSystemFetchFailedException extends DomainException
{
    /**
     * @see app/V1/Modules/ScrapingSystem/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'scraping-system::exceptions.other.listing_url_fetch_failed';

    public function __construct(
        ?string $message = null,
        array $replace = [],
    ) {
        parent::__construct($message ?? self::DEFAULT_MESSAGE_KEY, $replace);
    }
}
