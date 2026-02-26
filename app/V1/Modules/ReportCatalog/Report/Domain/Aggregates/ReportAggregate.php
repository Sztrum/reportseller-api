<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\Aggregates;

use App\V1\Modules\Platform\Domain\Exceptions\PlatformNotFoundException;
use App\V1\Modules\Platform\Domain\Models\Platform;
use Throwable;

readonly class ReportAggregate
{
    public function __construct(
    ) {
    }

    /**
     * @throws PlatformNotFoundException|Throwable
     */
    public function ensurePlatformExists(
        ?Platform $platform,
        string $exception = PlatformNotFoundException::class,
    ): void {
        throw_if($platform === null, $exception);
    }
}
