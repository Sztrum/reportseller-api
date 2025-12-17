<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class PlatformModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'platform';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {

    }
}
