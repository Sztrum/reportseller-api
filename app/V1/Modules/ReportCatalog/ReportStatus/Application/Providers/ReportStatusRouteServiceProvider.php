<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\ReportCatalog\ReportStatus\UI\Http\Controllers\ReportStatusController;
use Illuminate\Contracts\Routing\Registrar;

class ReportStatusRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [ReportStatusController::class, 'index'])->name('index');
    }
}
