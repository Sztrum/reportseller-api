<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Domain\Services;

use App\V1\Core\Domain\Enums\FrontEndRouteEnum;
use App\V1\Core\Domain\Models\Model;
use App\V1\Shared\VO\UrlVO;
use Illuminate\Config\Repository as ConfigInterface;
use Throwable;

readonly class FrontendEndpointService
{
    public function __construct(
        private ConfigInterface $config
    ) {
    }

    /**
     * @throws Throwable
     */
    public function getUrl(): UrlVO
    {
        $url = $this->config->get('core::frontend.url');
        return new UrlVO(is_string($url) ? $url : 'http://localhost');
    }

    /**
     * @throws Throwable
     * @param array<string, scalar|Model|null> $data
     */
    public function route(
        FrontEndRouteEnum $route,
        array $data = [],
        bool $missingToQuery = true
    ): string {
        return join('/', [
            $this->getUrl()->value,
            $this->prepareRoute($route, $data, $missingToQuery),
        ]);
    }

    /**
     * @param array<string, scalar|Model|null> $data
     */
    private function prepareRoute(
        FrontEndRouteEnum $route,
        array $data,
        bool $missingToQuery = true
    ): string {
        $url = $route->value;
        $query = [];

        foreach ($data as $paramName => $paramValue) {
            $pattern = "{{$paramName}}";
            if (strpos($url, $pattern) !== false) {
                $routeKey = $paramValue instanceof Model ? $paramValue->getRouteKey() : $paramValue;
                $value = is_scalar($routeKey) || $routeKey === null ? (string) $routeKey : '';
                $url = str_replace(
                    $pattern,
                    $value,
                    $url
                );
                //                unset($data[$paramName]);
            } elseif ($missingToQuery) {
                $query[$paramName] = $paramValue;
                //                unset($data[$paramName]);
            }
        }

        return ltrim($url . (!empty($query) ? '?' . http_build_query($query) : ''), '/');
    }
}
