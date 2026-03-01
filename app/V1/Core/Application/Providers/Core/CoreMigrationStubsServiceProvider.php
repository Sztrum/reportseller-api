<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CoreMigrationStubsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->app->extend(
            'migration.creator',
            function (MigrationCreator $migrationCreator, Application $app): MigrationCreator {
                $stubPath = $app['config']->get('core::migration.stub_path');
                return new MigrationCreator(
                    $migrationCreator->getFilesystem(),
                    is_string($stubPath) ? $stubPath : database_path('migrations')
                );
            }
        );
    }
}
