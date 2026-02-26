<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Providers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Providers\CommandBusServiceProvider;
use App\V1\Modules\ReportCatalog\Report\Application\Commands\Handlers\ReportStoreCommandHandler;
use App\V1\Modules\ReportCatalog\Report\Application\Commands\ReportStoreCommand;

class ReportCommandBusServiceProvider extends CommandBusServiceProvider
{
    public function registerCommands(CommandBusInterface $commandBus): void
    {
        $commandBus->map([
            ReportStoreCommand::class => ReportStoreCommandHandler::class,
        ]);
    }
}
