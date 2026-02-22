<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\ReportCatalog\Report\UI\Http\Requests\ReportStoreRequest;
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
    public function store(
        ReportStoreRequest $request
    ): JsonResponse {
        $this->commandBus->dispatch(
            new ResetPasswordCommand(
                test: 'test'
            )
        );

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.reset_password'),
        ]);
    }
}
