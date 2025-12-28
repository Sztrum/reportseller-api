<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class ListingModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'listing';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {

    }
}
