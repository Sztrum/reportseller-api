<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\ReportCatalog\ReportCategory\Application\Providers\ReportCategoryRouteServiceProvider;

class RaportCategoryModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'report-category';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(ReportCategoryRouteServiceProvider::class);
    }
}
