<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Platform\Infrastructure\Repositories\PlatformRepository;
use App\V1\Modules\Platform\UI\Http\Resources\CollectionPlatformResource;
use Illuminate\Contracts\Routing\ResponseFactory;

class PlatformController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly PlatformRepository $platformRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    public function index(): CollectionPlatformResource
    {
        return new CollectionPlatformResource(
            resource: $this->platformRepository->getActivePlatformsOrderedByOrder(),
            asResponse: true,
        );
    }
}
