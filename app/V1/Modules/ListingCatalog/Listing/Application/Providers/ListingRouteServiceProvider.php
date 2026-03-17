<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\ListingCatalog\Listing\UI\Http\Controllers\ListingController;
use Illuminate\Contracts\Routing\Registrar;

class ListingRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/autofill-from-url', [ListingController::class, 'autofillFromUrl'])->name('autofill-from-url');
    }
}
