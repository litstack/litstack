<?php

namespace AwStudio\Fjord\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class FjordAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('fjord.login');
        }
    }
}
