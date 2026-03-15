<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Platform\UI\Http\Controllers\PlatformController;
use Illuminate\Contracts\Routing\Registrar;

class PlatformRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [PlatformController::class, 'index'])->name('index');
    }
}
