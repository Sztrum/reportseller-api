<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Platform\PlatformModuleServiceProvider;
use App\V1\Modules\ReportCatalog\Report\ReportModuleServiceProvider;
use App\V1\Modules\ReportCatalog\ReportAttachments\ReportAttachmentsModuleServiceProvider;
use App\V1\Modules\ReportCatalog\ReportAuthors\ReportAuthorsModuleServiceProvider;
use App\V1\Modules\ReportCatalog\ReportCategory\RaportCategoryModuleServiceProvider;
use App\V1\Modules\ReportCatalog\ReportStatus\RaportStatusModuleServiceProvider;

class ReportCatalogModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'report-catalog';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(PlatformModuleServiceProvider::class);
        $this->app->register(RaportCategoryModuleServiceProvider::class);
        $this->app->register(RaportStatusModuleServiceProvider::class);
        $this->app->register(ReportModuleServiceProvider::class);
        $this->app->register(ReportAuthorsModuleServiceProvider::class);
        $this->app->register(ReportAttachmentsModuleServiceProvider::class);
    }
}
