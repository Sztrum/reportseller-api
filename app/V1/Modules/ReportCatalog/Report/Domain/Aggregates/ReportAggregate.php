<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\Aggregates;

use App\V1\Modules\ReportCatalog\Report\Domain\Exceptions\ReportNotFoundException;
use App\V1\Modules\ReportCatalog\Report\Domain\Exceptions\ReportReceiptAccessDeniedException;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Throwable;

class ReportAggregate
{
    /**
     * @throws ReportNotFoundException|Throwable
     */
    public function ensureReportExists(
        ?Report $report,
        string $exception = ReportNotFoundException::class,
    ): void {
        throw_if($report === null, $exception);
    }

    /**
     * @throws ReportReceiptAccessDeniedException|Throwable
     */
    public function ensureReceiptTokenMatches(
        Report $report,
        string $receiptToken,
        string $exception = ReportReceiptAccessDeniedException::class,
    ): void {
        throw_if(
            !hash_equals($report->receipt_token_hash, hash('sha256', $receiptToken)),
            $exception
        );
    }
}
