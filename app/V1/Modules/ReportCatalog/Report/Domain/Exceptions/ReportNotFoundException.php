<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\HttpException;
use Symfony\Component\HttpFoundation\Response;

class ReportNotFoundException extends HttpException
{
    /**
     * @see app/V1/Modules/ReportCatalog/Report/Application/Translations/pl/exceptions.php
     */
    private const DEFAULT_MESSAGE_KEY = 'report::exceptions.other.report_not_found';

    public function __construct(
        ?string $message = null,
        int $statusCode = Response::HTTP_NOT_FOUND,
    ) {
        parent::__construct(__($message ?? self::DEFAULT_MESSAGE_KEY), $statusCode);
    }
}
