<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Listing\Application\Services\ListingHistorySnapshotService;
use App\V1\Modules\Listing\Infrastructure\Repositories\ListingHistoryRepository;
use App\V1\Modules\Listing\Infrastructure\Repositories\ListingRepository;
use App\V1\Modules\Platform\Domain\Aggregates\PlatformAggregate;
use App\V1\Modules\Platform\Infrastructure\Repositories\PlatformRepository;
use App\V1\Modules\ReportCatalog\Report\Application\Commands\ReportStoreCommand;
use App\V1\Modules\ReportCatalog\Report\Infrastructure\Repositories\ReportRepository;
use App\V1\Modules\ReportCatalog\ReportAttachment\Infrastructure\Repositories\ReportAttachmentRepository;
use App\V1\Modules\ReportCatalog\ReportAuthor\Infrastructure\Repositories\ReportAuthorRepository;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Aggregates\ReportCategoryAggregate;
use App\V1\Modules\ReportCatalog\ReportCategory\Infrastructure\Repositories\ReportCategoryRepository;
use App\V1\Modules\ReportCatalog\ReportStatus\Infrastructure\Repositories\ReportStatusRepository;
use App\V1\Modules\Listing\Domain\Models\Listing;
use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models\ReportAuthor;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Throwable;

readonly class ReportStoreCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PlatformAggregate $platformAggregate,
        private ReportCategoryAggregate $reportCategoryAggregate,
        private ReportCategoryRepository $reportCategoryRepository,
        private ListingRepository $listingRepository,
        private ListingHistoryRepository $listingHistoryRepository,
        private ListingHistorySnapshotService $listingHistorySnapshotService,
        private PlatformRepository $platformRepository,
        private ReportStatusRepository $reportStatusRepository,
        private ReportRepository $reportRepository,
        private ReportAuthorRepository $reportAuthorRepository,
        private ReportAttachmentRepository $reportAttachmentRepository,
    ) {
    }

    /**
     * @param  ReportStoreCommand $command
     * @throws Throwable
     */
    public function handle(
        CommandInterface $command
    ): void {
        /** @var Platform|null $platform */
        $platform = $this->platformRepository->fistBySlug($command->listingDTO->platform_slug);
        $this->platformAggregate->ensurePlatformExists($platform);
        assert($platform instanceof Platform);

        /** @var ReportCategory|null $category */
        $category = $this->reportCategoryRepository->firstBySlug($command->listingDTO->report_category_slug);
        $this->reportCategoryAggregate->ensureReportCategoryExists($category);
        assert($category instanceof ReportCategory);

        /** @var Listing|null $listing */
        $listing = null;
        if ($command->listingDTO->external_id !== null) {
            $listing = $this->listingRepository->firstByExternalIdAndPlatform(
                $command->listingDTO->external_id,
                $platform->id
            );
        }

        if ($listing === null) {
            $listing = $this->listingRepository->firstByUrl($command->listingDTO->url);
        }

        if ($listing === null) {
            /** @var array<string, mixed> $listingPayload */
            $listingPayload = [
                'platform_id' => $platform->id,
                'report_category_id' => $category->id,
                'external_id' => $command->listingDTO->external_id,
                'url' => $command->listingDTO->url,
                'title' => $command->listingDTO->title,
                'description' => $command->listingDTO->description,
                'price' => $command->listingDTO->price,
                'currency' => $command->listingDTO->currency,
                'seller_type' => $command->listingDTO->seller_type,
                'seller_name' => $command->listingDTO->seller_name,
                'location' => $command->listingDTO->location,
                'thumbnail_url' => $command->listingDTO->thumbnail_url,
                'is_active_external' => $command->listingDTO->is_active_external,
            ];

            $listing = $this->listingRepository->create([
                ...$listingPayload,
            ]);
        }

        $snapshot = $this->listingHistorySnapshotService->getSnapshotIfChanged($listing->id, $command->listingDTO);
        if ($snapshot !== null) {
            /** @var array<string, mixed> $snapshot */
            $this->listingHistoryRepository->create($snapshot);
        }

        /** @var ReportStatus|null $reportStatus */
        $reportStatus = $this->reportStatusRepository->firstBySlug('new');
        assert($reportStatus instanceof ReportStatus);

        /** @var ReportAuthor $author */
        $author = $this->reportAuthorRepository->create([
            'ip_hash' => null,
            'consent_version' => $command->authorDTO->consent_version,
            'consent_given_at' => $command->authorDTO->consent_given_at,
            'is_blocked' => false,
            'blocked_at' => null,
        ]);

        $report = $this->reportRepository->create([
            'listing_id' => $listing->id,
            'report_status_id' => $reportStatus->id,
            'report_author_id' => $author->id,
            'title' => $command->reportDTO->title,
            'description' => $command->reportDTO->description,
            'moderation_note' => null,
            'is_published' => false,
            'published_at' => null,
        ]);

        foreach ($command->attachmentsDTO->items as $attachment) {
            $this->reportAttachmentRepository->create([
                'report_id' => $report->id,
                'storage_disk' => $attachment->storage_disk,
                'storage_key' => $attachment->storage_key,
                'original_name' => $attachment->original_name,
                'mime_type' => $attachment->mime_type,
                'size_bytes' => $attachment->size_bytes,
            ]);
        }
    }
}
