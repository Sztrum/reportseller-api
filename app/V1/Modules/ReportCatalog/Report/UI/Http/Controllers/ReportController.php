<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends ApiController
{

    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }
    public function store(ReportStoreRequest $request): JsonResponse
    {

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'data' => [
//                'content_sections' => $newContentSections,
//                'html' => view('content-sections::partials.api-html-response')->with([
//                    'inputsLayout' => $this->configRepository->get('core::modules-default-inputs.' . $request->get('module_name')),
//                    'moduleName' => $request->get('module_name'),
//                    'sectionsViewsDirectoryName' => $request->get('sections_views_directory_name'),
//                    'contentSections' => $newContentSections,
//                    'contentSectionTypes' => (new ContentSectionService())->getSectionsNamesV2($request->get('sections_views_directory_name')),
//                ])->render(),
            ],
        ]);
    }
}
