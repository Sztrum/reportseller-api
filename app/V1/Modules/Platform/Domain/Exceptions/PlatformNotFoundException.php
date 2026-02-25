<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class PlatformNotFoundException extends DomainException
{
    /**
     * @see app/V1/Modules/Platform/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'platform::exceptions.other.platform_not_found';

    public function __construct(
        ?string $message = null,
        array $replace = [],
    ) {
        parent::__construct($message ?? self::resolveDefaultMessage(self::DEFAULT_MESSAGE_KEY, $replace), $replace);
    }
}
