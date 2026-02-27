<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ReportCategoryNotFoundException extends DomainException
{
    /**
     * @see app/V1/Modules/ReportCatalog/ReportCategory/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'report-category::exceptions.other.category_not_found';

    public function __construct(
        ?string $message = null,
        array $replace = [],
    ) {
        parent::__construct($message ?? self::DEFAULT_MESSAGE_KEY, $replace);
    }
}
