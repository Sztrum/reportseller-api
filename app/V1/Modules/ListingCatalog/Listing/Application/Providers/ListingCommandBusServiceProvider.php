<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Application\Providers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Providers\CommandBusServiceProvider;
use App\V1\Modules\ListingCatalog\Listing\Application\Commands\Handlers\ListingAutofillFromUrlCommandHandler;
use App\V1\Modules\ListingCatalog\Listing\Application\Commands\ListingAutofillFromUrlCommand;

class ListingCommandBusServiceProvider extends CommandBusServiceProvider
{
    public function registerCommands(CommandBusInterface $commandBus): void
    {
        $commandBus->map([
            ListingAutofillFromUrlCommand::class => ListingAutofillFromUrlCommandHandler::class,
        ]);
    }
}
