<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class ScrapingSystemModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'scraping-system';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }
}
