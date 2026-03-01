<?php

declare(strict_types=1);

namespace App\V1\Core\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocaleByAcceptLanguageHeader
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language', 'pl');
        $enabledLanguages = config('core::languages.enabled_system_languages');

        if (is_array($enabledLanguages) && in_array($locale, $enabledLanguages, true)) {
            App::setLocale($locale);
        }

        /** @var Response $response */
        $response = $next($request);
        return $response;
    }
}
