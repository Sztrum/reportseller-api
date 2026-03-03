<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingHistory;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class ListingHistoryModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'listing-history';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }
}
