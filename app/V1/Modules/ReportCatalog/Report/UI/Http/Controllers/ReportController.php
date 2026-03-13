<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\ListingCatalog\Listing\Domain\DTO\ListingDTO;
use App\V1\Modules\ReportCatalog\Report\Application\Commands\ReportStoreCommand;
use App\V1\Modules\ReportCatalog\Report\Domain\Aggregates\ReportAggregate;
use App\V1\Modules\ReportCatalog\Report\Domain\DTO\ReportDTO;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use App\V1\Modules\ReportCatalog\Report\Infrastructure\Repositories\ReportRepository;
use App\V1\Modules\ReportCatalog\Report\UI\Http\Requests\ReportStoreRequest;
use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\DTO\AuthorDTO;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly ReportRepository $reportRepository,
        private readonly ReportAggregate $reportAggregate,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    public function store(
        ReportStoreRequest $request
    ): JsonResponse {
        $command = new ReportStoreCommand(
            listingDTO: ListingDTO::from($request->input('listing')),
            reportDTO: ReportDTO::from($request->input('report')),
            authorDTO: AuthorDTO::from($request->input('author', [])),
        );

        $this->commandBus->dispatch($command);

        return $this->responseFactory->json([
            'message' => __('report::messages.store_report_success'),
            'receipt_token' => $command->receiptToken,
        ], Response::HTTP_CREATED);
    }

    /**
     * @throws \Throwable
     */
    public function getReceipt(
        string $receipt_token
    ): JsonResponse {
        /** @var Report|null $report */
        $report = $this->reportRepository->firstByReceiptTokenWithReceiptRelations($receipt_token);
        $this->reportAggregate->ensureReportExists($report);

        return $this->responseFactory->json([
            'title' => $report->title,
            'description' => $report->description,
            'is_published' => $report->is_published,
            'published_at' => $report->published_at?->toISOString(),
            'listing' => [
                'url' => $report->listing->url,
                'title' => $report->listing->title,
                'price' => $report->listing->price,
                'currency' => $report->listing->currency,
                'platform_slug' => $report->listing->platform->slug,
            ],
            'report_status' => [
                'slug' => $report->reportStatus->slug,
            ],
        ], Response::HTTP_OK);
    }
}
