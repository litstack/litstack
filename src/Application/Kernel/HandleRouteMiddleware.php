<?php

namespace Lit\Application\Kernel;

use Closure;
use Illuminate\Support\Facades\Request;

class HandleRouteMiddleware
{
    /**
     * Execute Lit kernel method handleRoute.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        app()->get(\LitApp\Kernel::class)->handleRoute(Request::route());

        return $next($request);
    }
}
