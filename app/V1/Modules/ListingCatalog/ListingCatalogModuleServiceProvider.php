<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\ListingCatalog\Listing\ListingModuleServiceProvider;
use App\V1\Modules\ListingCatalog\ListingHistory\ListingHistoryModuleServiceProvider;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\ListingChangeProposalModuleServiceProvider;

class ListingCatalogModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'listing-catalog';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(ListingModuleServiceProvider::class);
        $this->app->register(ListingHistoryModuleServiceProvider::class);
        $this->app->register(ListingChangeProposalModuleServiceProvider::class);
    }
}
