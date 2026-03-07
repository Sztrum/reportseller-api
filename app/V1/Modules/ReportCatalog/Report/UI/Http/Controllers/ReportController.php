<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\ListingCatalog\Listing\Domain\DTO\ListingDTO;
use App\V1\Modules\ReportCatalog\Report\Application\Commands\ReportStoreCommand;
use App\V1\Modules\ReportCatalog\Report\Domain\DTO\ReportDTO;
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
    ) {
        parent::__construct($commandBus, $responseFactory);
    }
    public function store(
        ReportStoreRequest $request
    ): JsonResponse {
        $this->commandBus->dispatch(
            new ReportStoreCommand(
                listingDTO: ListingDTO::from($request->input('listing')),
                reportDTO: ReportDTO::from($request->input('report')),
                authorDTO: AuthorDTO::from($request->input('author', [])),
            )
        );

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('report-catalog::messages.store_report_success'),
        ]);
    }
}
