<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAttachment;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class ReportAttachmentModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'report-attachment';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();
    }
}
