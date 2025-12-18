<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\User\UI\Http\Controllers\UserController;
use Illuminate\Contracts\Routing\Registrar;

class ReportRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->group([
            'prefix' => '{some}',
        ], function (Registrar $router) {
            $router->get('/reports', [UserController::class, 'reports'])->name('reports');
        });
    }
}
