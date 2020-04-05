<?php

namespace Fjord\Application\Kernel;

use Illuminate\Support\Facades\Request;
use App\Providers\RouteServiceProvider;
use Closure;

class HandleRouteMiddleware
{
    /**
     * Execute Fjord\Application\Kernel method handleRoute.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        app()->get('fjord.kernel')->handleRoute(Request::route());

        return $next($request);
    }
}
