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
use App\V1\Modules\ReportCatalog\Report\UI\Http\Requests\ReportReceiptShowRequest;
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
            'receipt_url' => route('api.reports.receipt.show', [
                'report_id' => $command->reportId,
            ]),
            'receipt_token' => $command->receiptToken,
        ], Response::HTTP_CREATED);
    }

    /**
     * @throws \Throwable
     */
    public function getReceipt(
        ReportReceiptShowRequest $request
    ): JsonResponse {
        /** @var Report|null $report */
        $report = $this->reportRepository->firstByIdWithReceiptRelations(
            (string) $request->route('report_id')
        );
        $this->reportAggregate->ensureReportExists($report);
        $this->reportAggregate->ensureReceiptTokenMatches($report, (string) $request->input('receipt_token'));

        return $this->responseFactory->json([
            'id' => $report->id,
            'title' => $report->title,
            'description' => $report->description,
            'is_published' => $report->is_published,
            'published_at' => $report->published_at?->toISOString(),
            'listing' => [
                'id' => $report->listing->id,
                'url' => $report->listing->url,
                'title' => $report->listing->title,
                'price' => $report->listing->price,
                'currency' => $report->listing->currency,
                'platform_slug' => $report->listing->platform->slug,
            ],
            'report_status' => [
                'id' => $report->reportStatus->id,
                'slug' => $report->reportStatus->slug,
            ],
        ], Response::HTTP_OK);
    }
}
