<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAuthor;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class ReportAuthorModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'report-author';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();
    }
}
