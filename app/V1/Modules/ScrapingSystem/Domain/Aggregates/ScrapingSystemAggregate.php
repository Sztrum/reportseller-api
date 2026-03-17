<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Domain\Aggregates;

use App\V1\Modules\ScrapingSystem\Domain\Exceptions\ScrapingSystemFetchFailedException;
use App\V1\Modules\ScrapingSystem\Domain\Exceptions\ScrapingSystemPlatformNotSupportedException;
use App\V1\Modules\Platform\Domain\Models\Platform;
use Throwable;

readonly class ScrapingSystemAggregate
{
    /**
     * @throws ScrapingSystemPlatformNotSupportedException|Throwable
     */
    public function ensurePlatformSupported(
        ?Platform $platform,
        string $exception = ScrapingSystemPlatformNotSupportedException::class,
    ): void {
        throw_if($platform === null, $exception);
    }

    /**
     * @throws ScrapingSystemFetchFailedException|Throwable
     */
    public function ensureFetchedSuccessfully(
        bool $response,
        string $exception = ScrapingSystemFetchFailedException::class,
    ): void {
        throw_if(!$response, $exception);
    }
}
