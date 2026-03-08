<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Routes;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class FrontendRouteServiceProvider extends RouteServiceProvider
{
    protected bool $pluralPrefix = true;

    protected bool $prefix = true;
    protected bool $pluralRouteName = true;

    protected string $prefixRouteName = '';

    protected string $additionalPrefix = '';

    public function getModuleProvider(): ModuleServiceProvider
    {
        $routeProviderReflection = new ReflectionClass($this);

        $provider = Collection::make($this->app->getProviders(ModuleServiceProvider::class))
            ->filter(function ($moduleServiceProvider) use ($routeProviderReflection): bool {
                if (!$moduleServiceProvider instanceof ModuleServiceProvider) {
                    return false;
                }

                $routeProviderNamespace = $routeProviderReflection->getNamespaceName();
                $moduleProviderNamespace = (new ReflectionClass($moduleServiceProvider))->getNamespaceName();

                $routeProviderSegments = explode('\\', $routeProviderNamespace);
                $moduleProviderSegments = explode('\\', $moduleProviderNamespace);

                foreach ($moduleProviderSegments as $index => $segment) {
                    if (!isset($routeProviderSegments[$index]) || $routeProviderSegments[$index] !== $segment) {
                        return false;
                    }
                }

                return true;
            })->first();

        if (!$provider instanceof ModuleServiceProvider) {
            throw new \RuntimeException(__('core::domain.frontend_route_module_provider_not_resolved'));
        }

        return $provider;
    }

    public function getModulePrefix(): string
    {
        $moduleName = $this->getModuleProvider()
            ->moduleName();

        $modulePrefix = $this->pluralPrefix ? Str::plural($moduleName) : $moduleName;

        return $this->additionalPrefix !== '' ? $this->additionalPrefix . $modulePrefix : $modulePrefix;
    }

    public function getModuleRouteName(): string
    {
        $moduleName = $this->getModuleProvider()
            ->moduleName();

        return $this->pluralRouteName ? Str::plural($moduleName) : $moduleName;
    }

    public function map(Registrar $router): void
    {
        $router->group([
            'middleware' => $this->middlewares(),
            'prefix' => $this->getModulePrefix(),
            'as' => $this->prefixRouteName !== '' ? $this->prefixRouteName . $this->getModuleRouteName() . '.' : $this->getModuleRouteName() . '.'
        ], fn () => $this->registerRoutes($router));
    }

    abstract protected function registerRoutes(Registrar $router): void;

    /**
     * @return list<string>
     */
    protected function middlewares(): array
    {
        return ['web'];
    }
}
