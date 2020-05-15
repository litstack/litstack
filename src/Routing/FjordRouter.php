<?php

namespace Fjord\Routing;

use Form;
use Closure;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Str;
use Fjord\Support\Facades\Package;
use Illuminate\Support\Facades\Route;

class FjordRouter
{
    /**
     * Middlewares that are used by Fjord routes for authenticated users.
     *
     * @var array
     */
    protected $middlewares = [
        'web',
        'fjord.auth:fjord',
    ];

    /**
     * Public middlewares.
     *
     * @var array
     */
    protected $publicMiddlewares = [
        'web',
    ];

    /**
     * Initialize defaults for a Fjord route. 
     * Fjord Routes should always be created 
     * with \Fjord\Support\Facades\FjordRoute.
     *
     * @return \Illuminate\Support\Facades\Route
     */
    public function __call($method, $parameters)
    {
        $route = $this->getRoutePreset();

        return $route->$method(...$parameters);
    }

    /**
     * Get route preset.
     *
     * @return \Illuminate\Support\Facades\Route
     */
    protected function getRoutePreset()
    {
        return Route::prefix(config('fjord.route_prefix'))
            ->as('fjord.')
            ->middleware($this->middlewares);
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  Closure|array|string  $attributes
     * @param  Closure  $closure
     * @return void
     */
    public function group($attributes, Closure $closure = null)
    {
        if (is_callable($attributes) || is_string($attributes)) {
            return $this->getRoutePreset()->group($attributes);
        }
        $attributes['prefix'] = config('fjord.route_prefix') . '/' . ($attributes['prefix'] ?? '');
        $attributes['as'] = 'fjord.' . ($attributes['as'] ?? '');
        $attributes['middleware'] = array_merge($attributes['middlewares'] ?? [], $this->middlewares);
        Route::group($attributes, $closure);
    }

    /**
     * Initialize defaults for a Fjord package route.
     * Routes for a Fjord package should always be created 
     * with \Fjord\Support\Facades\FjordRoute@package
     * 
     * @param string $package
     * @return Illuminate\Support\Facades\Route $route
     */
    public function package($package)
    {
        $package = Package::get($package);

        return Route::prefix($package->getRoutePrefix())
            ->as($package->getRouteAs())
            ->middleware($this->middlewares);
    }

    /**
     * Public route using Fjord route prefix.
     * 
     * @return \Illuminate\Support\Facades\Route
     */
    public function public()
    {
        return Route::prefix(config('fjord.route_prefix'))
            ->as('fjord.')
            ->middleware($this->publicMiddlewares);
    }
}
