<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Application\Services;

use App\V1\Modules\ScrapingSystem\Domain\Contracts\ScrapingSystemListingScraperInterface;
use App\V1\Modules\ScrapingSystem\Domain\Exceptions\ScrapingSystemPlatformNotSupportedException;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemAllegroLokalnieListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemAllegroListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemGratkaListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemLokalneOgloszeniaListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemOlxListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemOtodomListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemOtomotoListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemSprzedajemyListingScraper;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\ScrapingSystemVintedListingScraper;
use App\V1\Modules\Platform\Domain\Models\Platform;

readonly class ScrapingSystemListingScraperRegistry
{
    /**
     * @param array<int, ScrapingSystemListingScraperInterface> $scrapers
     */
    public function __construct(
        private ScrapingSystemAllegroListingScraper           $scrapingSystemAllegroListingScraper,
        private ScrapingSystemAllegroLokalnieListingScraper   $scrapingSystemAllegroLokalnieListingScraper,
        private ScrapingSystemSprzedajemyListingScraper       $scrapingSystemSprzedajemyListingScraper,
        private ScrapingSystemGratkaListingScraper            $scrapingSystemGratkaListingScraper,
        private ScrapingSystemLokalneOgloszeniaListingScraper $scrapingSystemLokalneOgloszeniaListingScraper,
        private ScrapingSystemOlxListingScraper               $scrapingSystemOlxListingScraper,
        private ScrapingSystemOtodomListingScraper            $scrapingSystemOtodomListingScraper,
        private ScrapingSystemOtomotoListingScraper           $scrapingSystemOtomotoListingScraper,
        private ScrapingSystemVintedListingScraper            $scrapingSystemVintedListingScraper,
        private array                                         $scrapers = [],
    ) {}

    /**
     * @throws ScrapingSystemPlatformNotSupportedException
     */
    public function resolveForPlatform(
        Platform $platform
    ): ScrapingSystemListingScraperInterface {
        $scrapers = $this->scrapers !== []
            ? $this->scrapers
            : [
                $this->scrapingSystemAllegroListingScraper,
                $this->scrapingSystemAllegroLokalnieListingScraper,
                $this->scrapingSystemSprzedajemyListingScraper,
                $this->scrapingSystemGratkaListingScraper,
                $this->scrapingSystemLokalneOgloszeniaListingScraper,
                $this->scrapingSystemOlxListingScraper,
                $this->scrapingSystemOtodomListingScraper,
                $this->scrapingSystemOtomotoListingScraper,
                $this->scrapingSystemVintedListingScraper,
            ];

        foreach ($scrapers as $scraper) {
            if ($scraper->supports($platform)) {
                return $scraper;
            }
        }

        throw new ScrapingSystemPlatformNotSupportedException();
    }
}
