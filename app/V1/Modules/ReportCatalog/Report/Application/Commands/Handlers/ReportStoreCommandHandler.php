<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\ListingCatalog\Listing\Infrastructure\Repositories\ListingRepository;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Application\Services\ListingChangeProposalDiffService;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Infrastructure\Repositories\ListingChangeProposalRepository;
use App\V1\Modules\Platform\Domain\Aggregates\PlatformAggregate;
use App\V1\Modules\Platform\Infrastructure\Repositories\PlatformRepository;
use App\V1\Modules\ReportCatalog\Report\Application\Commands\ReportStoreCommand;
use App\V1\Modules\ReportCatalog\Report\Infrastructure\Repositories\ReportRepository;
use App\V1\Modules\ReportCatalog\ReportAuthor\Infrastructure\Repositories\ReportAuthorRepository;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Aggregates\ReportCategoryAggregate;
use App\V1\Modules\ReportCatalog\ReportCategory\Infrastructure\Repositories\ReportCategoryRepository;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Aggregates\ReportStatusAggregate;
use App\V1\Modules\ReportCatalog\ReportStatus\Infrastructure\Repositories\ReportStatusRepository;
use Throwable;

readonly class ReportStoreCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PlatformAggregate $platformAggregate,
        private ReportCategoryAggregate $reportCategoryAggregate,
        private ReportStatusAggregate $reportStatusAggregate,
        private ReportCategoryRepository $reportCategoryRepository,
        private ListingRepository $listingRepository,
        private ListingChangeProposalDiffService $listingChangeProposalDiffService,
        private ListingChangeProposalRepository $listingChangeProposalRepository,
        private PlatformRepository $platformRepository,
        private ReportStatusRepository $reportStatusRepository,
        private ReportRepository $reportRepository,
        private ReportAuthorRepository $reportAuthorRepository,
    ) {
    }

    /**
     * @param  ReportStoreCommand $command
     * @throws Throwable
     */
    public function handle(
        CommandInterface $command
    ): void {
        $platform = $this->platformRepository->fistBySlug($command->listingDTO->platform_slug);
        $this->platformAggregate->ensurePlatformExists($platform);
        assert($platform !== null);

        $category = $this->reportCategoryRepository->firstBySlug($command->listingDTO->report_category_slug);
        $this->reportCategoryAggregate->ensureReportCategoryExists($category);
        assert($category !== null);

        $listing = null;
        if ($command->listingDTO->external_id !== null) {
            $listing = $this->listingRepository->firstByExternalIdAndPlatform(
                $command->listingDTO->external_id,
                $platform->id
            );
        }
        if ($listing === null) {
            $listing = $this->listingRepository->firstByPlatformAndUrl(
                $platform->id,
                $command->listingDTO->url
            );
        }

        $listingPayload = [
            'platform_id' => $platform->id,
            'report_category_id' => $category->id,
            'external_id' => $command->listingDTO->external_id,
            'url' => $command->listingDTO->url,
            'title' => $command->listingDTO->title,
            'description' => $command->listingDTO->description,
            'price' => $command->listingDTO->price,
            'currency' => $command->listingDTO->currency,
            'seller_type' => $command->listingDTO->seller_type?->value,
            'seller_name' => $command->listingDTO->seller_name,
            'location' => $command->listingDTO->location,
            'thumbnail_url' => $command->listingDTO->thumbnail_url,
            'is_active_external' => $command->listingDTO->is_active_external,
        ];
        ksort($listingPayload);

        if ($listing === null) {
            $listing = $this->listingRepository->create($listingPayload);
        }

        $reportStatus = $this->reportStatusRepository->firstBySlug('new');
        $this->reportStatusAggregate->ensureReportStatusExists($reportStatus, 'new');

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

        $latestPendingProposal = $this->listingChangeProposalRepository->latestPendingForListing($listing->id);
        $shouldCreateProposal = $this->listingChangeProposalDiffService->hasListingPayloadChanges($listing, $listingPayload);
        $hasDuplicatePendingProposal = false;

        if ($latestPendingProposal !== null) {
            $latestPendingProposalPayload = $latestPendingProposal->proposed_payload;
            ksort($latestPendingProposalPayload);
            $hasDuplicatePendingProposal = $latestPendingProposalPayload === $listingPayload;
        }

        if (!$shouldCreateProposal || $hasDuplicatePendingProposal) {
            return;
        }

        $currentPayload = [
            'platform_id' => $listing->platform_id,
            'report_category_id' => $listing->report_category_id,
            'external_id' => $listing->external_id,
            'url' => $listing->url,
            'title' => $listing->title,
            'description' => $listing->description,
            'price' => $listing->price,
            'currency' => $listing->currency,
            'seller_type' => $listing->seller_type?->value,
            'seller_name' => $listing->seller_name,
            'location' => $listing->location,
            'thumbnail_url' => $listing->thumbnail_url,
            'is_active_external' => $listing->is_active_external,
        ];
        ksort($currentPayload);

        $this->listingChangeProposalRepository->create([
            'listing_id' => $listing->id,
            'report_id' => $report->id,
            'status' => ListingChangeProposal::STATUS_PENDING,
            'current_payload' => $currentPayload,
            'proposed_payload' => $listingPayload,
            'requested_at' => now(),
            'reviewed_at' => null,
            'reviewed_by' => null,
            'review_reason' => null,
        ]);
    }
}
