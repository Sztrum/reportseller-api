<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\ReportCatalog\Report\Application\Providers\ReportRouteServiceProvider;

class ReportModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'report';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(ReportRouteServiceProvider::class);
    }
}
