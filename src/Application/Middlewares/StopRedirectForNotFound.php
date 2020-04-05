<?php

namespace AwStudio\Fjord\Application\Middlewares;

use Closure;

class StopRedirectForNotFound
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd(debug_backtrace());
        return $next($request);
    }
}
