<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\ListingCatalog\Listing\Application\Providers\ListingCommandBusServiceProvider;
use App\V1\Modules\ListingCatalog\Listing\Application\Providers\ListingRouteServiceProvider;

class ListingModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'listing';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(ListingCommandBusServiceProvider::class);
        $this->app->register(ListingRouteServiceProvider::class);
    }
}
