<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ReportStatusNotFoundException extends DomainException
{
    /**
     * @see app/V1/Modules/ReportCatalog/ReportStatus/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'report-status::exceptions.other.status_not_found';

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
