<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\ReportCatalog\ReportCategory\Infrastructure\Repositories\ReportCategoryRepository;
use App\V1\Modules\ReportCatalog\ReportCategory\UI\Http\Resources\CollectionReportCategoryResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class ReportCategoryController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly ReportCategoryRepository $reportCategoryRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    public function index(): CollectionReportCategoryResource {
        return new CollectionReportCategoryResource(
            resource: $this->reportCategoryRepository->getActiveReportCategoriesOrderedByOrder(),
            asResponse: true,
        );
    }
}
