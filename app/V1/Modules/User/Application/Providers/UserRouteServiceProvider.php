<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use Illuminate\Contracts\Routing\Registrar;

class UserRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        //
    }
}
