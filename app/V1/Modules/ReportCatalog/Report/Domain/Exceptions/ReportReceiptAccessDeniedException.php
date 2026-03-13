<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ForbiddenException;

class ReportReceiptAccessDeniedException extends ForbiddenException
{
    /**
     * @see app/V1/Modules/ReportCatalog/Report/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'report::exceptions.other.receipt_access_denied';

    public function __construct(?string $message = null)
    {
        parent::__construct(__($message ?? self::DEFAULT_MESSAGE_KEY));
    }
}
