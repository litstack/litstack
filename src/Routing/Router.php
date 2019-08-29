<?php

namespace AwStudio\Fjord\Routing;

use Illuminate\Support\Facades\Route;

class Router
{
    /**
     * Initialize defaults for a Fjord route.
     * Fjord Routes should always be created with
     * \AwStudio\Fjord\Support\Facades\FjordRoute.
     *
     * @return Illuminate\Support\Facades\Route $route
     */
    public function __call($method, $parameters)
    {
        $route = Route::prefix(config('fjord.route_prefix'))
            ->as('fjord.')
            ->middleware(['web', 'fjord.auth']);

        return $route->$method(...$parameters);
    }

    public function public()
    {
        return Route::prefix(config('fjord.route_prefix'))
            ->as('fjord.')
            ->middleware('web');
    }
}
