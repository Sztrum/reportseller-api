<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\ListingCatalog\Listing\Application\Commands\ListingAutofillFromUrlCommand;
use App\V1\Modules\ListingCatalog\Listing\Domain\DTO\ListingDTO;
use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Modules\ScrapingSystem\Application\Services\ScrapingSystemService;
use Throwable;

readonly class ListingAutofillFromUrlCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ScrapingSystemService $scrapingSystemService,
    ) {
    }

    /**
     * @param ListingAutofillFromUrlCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        $scrapedListing = $this->scrapingSystemService->scrapeFromUrl($command->url);

        $command->listingDTO = ListingDTO::from([
            'url' => $scrapedListing->url,
            'platform_slug' => $scrapedListing->platform_slug,
            'report_category_slug' => $scrapedListing->report_category_slug ?? 'ogolne',
            'external_id' => $scrapedListing->external_id,
            'title' => $scrapedListing->title,
            'description' => $scrapedListing->description,
            'price' => $scrapedListing->price,
            'currency' => $scrapedListing->currency,
            'seller_type' => ListingSellerTypeEnum::tryFrom((string) $scrapedListing->seller_type),
            'seller_name' => $scrapedListing->seller_name,
            'location' => $scrapedListing->location,
            'thumbnail_url' => $scrapedListing->thumbnail_url,
            'is_active_external' => $scrapedListing->is_active_external,
        ]);
    }
}
