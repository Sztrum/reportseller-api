<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class PlatformBySlugNotFoundException extends DomainException
{
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = __('platform::exceptions.other.platform_slug_not_found', $replace);
        }

        parent::__construct($message, $replace);
    }
}
