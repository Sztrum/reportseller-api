<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class RaportCategoryModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'report-category';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {

    }
}
