<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\ReportCatalog\ReportCategory\UI\Http\Controllers\ReportCategoryController;
use Illuminate\Contracts\Routing\Registrar;

class ReportCategoryRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [ReportCategoryController::class, 'index'])->name('index');
    }
}
