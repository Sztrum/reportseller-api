<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\Domain\Aggregates;

use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Exceptions\ReportCategoryNotFoundException;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Throwable;

readonly class ReportCategoryAggregate
{
    /**
     * @phpstan-assert !null $category
     * @throws ReportCategoryNotFoundException|Throwable
     */
    public function ensureReportCategoryExists(
        ?ReportCategory $category,
        string $exception = ReportCategoryNotFoundException::class,
    ): void {
        throw_if($category === null, $exception);
    }
}
