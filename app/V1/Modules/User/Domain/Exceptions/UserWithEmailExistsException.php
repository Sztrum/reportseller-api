<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class UserWithEmailExistsException extends DomainException
{
    /**
     * @see app/V1/Modules/User/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'user::exceptions.other.user_with_email_already_exists';

    public function __construct(
        ?string $message = null,
        array $replace = []
    ) {
        parent::__construct($message ?? self::resolveDefaultMessage(self::DEFAULT_MESSAGE_KEY, $replace), $replace);
    }
}
