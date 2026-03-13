<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\ReportCatalog\Report\UI\Http\Controllers\ReportController;
use Illuminate\Contracts\Routing\Registrar;

class ReportRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->group([
            'middleware' => ['auth:sanctum'],
        ], function (Registrar $router) {
//            $router->post('/delete', [ReportController::class, 'delete'])->name('delete');
        });
        $router->post('/store', [ReportController::class, 'store'])->name('store');
        $router->get('/{report_id}/get-receipt', [ReportController::class, 'getReceipt'])->name('receipt.show');
    }
}
