<?php

namespace Fjord\Application\Kernel;

use Closure;
use Illuminate\Support\Facades\Request;

class HandleRouteMiddleware
{
    /**
     * Execute Fjord kernel method handleRoute.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        app()->get(\FjordApp\Kernel::class)->handleRoute(Request::route());

        return $next($request);
    }
}
