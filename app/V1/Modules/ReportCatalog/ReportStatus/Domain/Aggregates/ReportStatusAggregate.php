<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\Domain\Aggregates;

use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Exceptions\ReportStatusNotFoundException;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Throwable;

readonly class ReportStatusAggregate
{
    /**
     * @phpstan-assert !null $reportStatus
     * @throws ReportStatusNotFoundException|Throwable
     */
    public function ensureReportStatusExists(
        ?ReportStatus $reportStatus,
        string $slug,
        string $exception = ReportStatusNotFoundException::class,
    ): void {
        throw_if($reportStatus === null, $exception, null, ['slug' => $slug]);
    }
}
