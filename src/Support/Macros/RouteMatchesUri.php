<?php

namespace Ignite\Support\Macros;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

/**
 * @macro bool matchesUri(string $uri, string $method = null)
 *
 * @see \Illuminate\Routing\Matching\MethodValidator
 * @see \Illuminate\Routing\Matching\UriValidator
 */
class RouteMatchesUri
{
    /**
     * Register macro.
     *
     * @return void
     */
    public function register()
    {
        Route::macro('matchesUri', function ($uri, string $method = null) {
            $this->compileRoute();

            if ($method && ! in_array($method, $this->methods())) {
                return false;
            }

            $path = rtrim(parse_url($uri)['path'] ?? '', '/') ?: '/';

            if (! preg_match($this->getCompiled()->getRegex(), rawurldecode($path))) {
                return false;
            }

            return true;
        });

        RouteFacade::macro('matchesUri', function ($uri, string $method = null) {
            return collect($this->getRoutes()->getRoutes())->first(
                fn (Route $route) => $route->matchesUri($uri, $method)
            );
        });
    }
}
