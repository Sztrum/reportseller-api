<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\ReportCatalog\ReportStatus\Infrastructure\Repositories\ReportStatusRepository;
use App\V1\Modules\ReportCatalog\ReportStatus\UI\Http\Resources\CollectionReportStatusResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class ReportStatusController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly ReportStatusRepository $reportStatusRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    public function index(): CollectionReportStatusResource {
        return new CollectionReportStatusResource(
            resource: $this->reportStatusRepository->getActiveReportStatusesOrderedByName(),
            asResponse: true,
        );
    }
}
