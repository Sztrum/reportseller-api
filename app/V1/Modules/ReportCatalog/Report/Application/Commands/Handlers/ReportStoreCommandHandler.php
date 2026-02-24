<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Listing\Infrastructure\Repositories\ListingRepository;
use App\V1\Modules\ReportCatalog\Report\Application\Commands\ReportStoreCommand;
use App\V1\Modules\ReportCatalog\Report\Domain\Aggregates\ReportAggregate;
use App\V1\Modules\ReportCatalog\ReportCategory\Infrastructure\Repositories\ReportCategoryRepository;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

readonly class ReportStoreCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ReportAggregate $reportAggregate,
        private ReportCategoryRepository $reportCategoryRepository,
        private ListingRepository $listingRepository,
    ) {
    }

    /**
     * @param  ReportStoreCommand $command
     * @throws Throwable
     */
    #[NoReturn]
    public function handle(
        CommandInterface $command
    ): void {
        $this->reportAggregate->checkIfPlatformExistsBySlug($command->listingDTO->platform_slug);


        // TODO: Move to aggregate
        $category = $this->reportCategoryRepository->firstBySlug(
            $command->listingDTO->report_category_slug
        );
//        if ($category === null) {
//            throw new ReportCategoryNotFoundException(
//                $command->listingDTO->report_category_slug
//            );
//        }

        // TODO: move to aggregate
        // TODO: save listing, and report, and author, and attachments
        $listing = $this->listingRepository->firstByExternalId($command->listingDTO->external_id);

        if($listing === null) {
            $this->listingRepository->create([
                "platform_id" => $platform->id,
                "report_category_id" => $category->id,
                'external_id' => $command->listingDTO->external_id,
                'url' => $command->listingDTO->url,
                'title' => $command->listingDTO->title,
                'price' => $command->listingDTO->price,
                'currency' => $command->listingDTO->currency,
                'seller_type' => $command->listingDTO->seller_type,
                'seller_name' => $command->listingDTO->seller_name,
                'location' => $command->listingDTO->location,
                'thumbnail_url' => $command->listingDTO->thumbnail_url,
                'is_active_external' => $command->listingDTO->is_active_external,
            ]);
        }

    }
}
