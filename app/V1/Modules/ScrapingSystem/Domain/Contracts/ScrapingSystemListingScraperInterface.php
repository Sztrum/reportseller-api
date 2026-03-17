<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Domain\Contracts;

use App\V1\Modules\ScrapingSystem\Domain\DTO\ScrapingSystemScrapedListingDTO;
use App\V1\Modules\Platform\Domain\Models\Platform;

interface ScrapingSystemListingScraperInterface
{
    public function supports(Platform $platform): bool;

    public function scrape(
        string $html,
        string $url,
        Platform $platform,
    ): ScrapingSystemScrapedListingDTO;
}
