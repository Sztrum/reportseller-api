<?php

return [
    App\V1\Core\CoreModuleServiceProvider::class,
    App\V1\Core\Infrastructure\Packages\Vite\VitePackageServiceProvider::class,
    App\V1\Modules\Auth\AuthModuleServiceProvider::class,
    App\V1\Modules\Country\CountryModuleServiceProvider::class,
    App\V1\Modules\Email\EmailModuleServiceProvider::class,
    App\V1\Modules\ListingCatalog\ListingCatalogModuleServiceProvider::class,
    App\V1\Modules\ReportCatalog\ReportCatalogModuleServiceProvider::class,
    App\V1\Modules\ScrapingSystem\ScrapingSystemModuleServiceProvider::class,
    App\V1\Modules\User\UserModuleServiceProvider::class,
];
