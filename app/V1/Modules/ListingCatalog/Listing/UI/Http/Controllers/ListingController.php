<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\UI\Http\Controllers;

use App\V1\Modules\ListingCatalog\Listing\Application\Commands\ListingAutofillFromUrlCommand;
use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\ListingCatalog\Listing\Domain\DTO\ListingDTO;
use App\V1\Modules\ListingCatalog\Listing\UI\Http\Requests\ListingAutofillFromUrlRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListingController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    /**
     * @throws \Throwable
     */
    public function autofillFromUrl(
        ListingAutofillFromUrlRequest $request,
    ): JsonResponse {
        $command = new ListingAutofillFromUrlCommand(
            url: (string) $request->query('url'),
        );

        $this->commandBus->dispatch($command);

        return $this->responseData([
            'listing' => $command->listingDTO?->toArray(),
        ], __('listing::messages.autofill_from_url_success'));
    }
}
