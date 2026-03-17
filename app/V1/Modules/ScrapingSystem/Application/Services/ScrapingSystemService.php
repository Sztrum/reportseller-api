<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Application\Services;

use App\V1\Modules\ScrapingSystem\Domain\Aggregates\ScrapingSystemAggregate;
use App\V1\Modules\ScrapingSystem\Domain\DTO\ScrapingSystemScrapedListingDTO;
use App\V1\Modules\ScrapingSystem\Domain\Exceptions\ScrapingSystemFetchFailedException;
use App\V1\Modules\ScrapingSystem\Domain\Exceptions\ScrapingSystemPlatformNotSupportedException;
use App\V1\Modules\Platform\Domain\Models\Platform;
use Illuminate\Support\Facades\Http;
use Throwable;

readonly class ScrapingSystemService
{
    public function __construct(
        private ScrapingSystemAggregate $scrapingSystemAggregate,
        private ScrapingSystemPlatformResolver $scrapingSystemPlatformResolver,
        private ScrapingSystemListingScraperRegistry $scrapingSystemListingScraperRegistry,
    ) {}

    /**
     * @throws ScrapingSystemFetchFailedException
     * @throws ScrapingSystemPlatformNotSupportedException
     * @throws Throwable
     */
    public function scrapeFromUrl(string $url): ScrapingSystemScrapedListingDTO
    {
        $platform = $this->scrapingSystemPlatformResolver->resolvePlatformForUrl($url);
        $this->scrapingSystemAggregate->ensurePlatformSupported($platform);

        try {
            $response = Http::timeout(10)
                ->accept('text/html')
                ->get($url);
        } catch (Throwable) {
            throw new ScrapingSystemFetchFailedException();
        }

        $this->scrapingSystemAggregate->ensureFetchedSuccessfully($response->ok());

        /** @var Platform $platform */
        return $this->scrapingSystemListingScraperRegistry
            ->resolveForPlatform($platform)
            ->scrape($response->body(), $url, $platform);
    }
}
