<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ForbiddenException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthException extends ForbiddenException
{
    private const DEFAULT_MESSAGE_KEY = 'auth::messages.auth_failed';

    public function __construct(
        ?string $message = null,
        public int $statusCode = Response::HTTP_FORBIDDEN,
        ?Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct(
            $message ?? self::resolveDefaultMessage(self::DEFAULT_MESSAGE_KEY),
            $statusCode,
            $previous,
            $headers,
            $code
        );
    }
}
