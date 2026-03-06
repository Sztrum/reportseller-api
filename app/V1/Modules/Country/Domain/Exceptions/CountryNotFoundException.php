<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class CountryNotFoundException extends DomainException
{
    private const DEFAULT_MESSAGE_KEY = 'countries::exceptions.other.country_not_found';

    /**
     * @param array<string, scalar> $replace
     */
    public function __construct(
        ?string $message = null,
        array $replace = [],
    ) {
        parent::__construct($message ?? self::DEFAULT_MESSAGE_KEY, $replace);
    }
}
