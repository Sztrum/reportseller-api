<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class RaportStatusModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'report-status';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {

    }
}
