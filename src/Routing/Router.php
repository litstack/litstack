<?php

namespace AwStudio\Fjord\Routing;

use Form;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use AwStudio\Fjord\Fjord\Extend\Package;

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
            ->middleware(['web', 'fjord.auth:fjord']);

        return $route->$method(...$parameters);
    }

    public function package(Package $package)
    {
        return Route::prefix($package->getRoutePrefix())
            ->as($package->getRouteAs())
            ->middleware(['web', 'fjord.auth:fjord']);
    }

    public function public()
    {
        return Route::prefix(config('fjord.route_prefix'))
            ->as('fjord.')
            ->middleware('web');
    }

    /**
     * Register crud action routes.
     *
     * @param  string $crud
     * @param  string $namespace
     *
     * @return
     */
    public function extensionRoutes(string $class)
    {
        $reflection = new ReflectionClass($class);

        foreach($reflection->getMethods() as $method) {
            if(! Str::startsWith($method->name, 'make') && ! Str::endsWith($method->name, 'Route')) {
                continue;
            }

            $instance = with(new $class());
            call_user_func_array([$instance, $method->name], []);
        }
    }
}
