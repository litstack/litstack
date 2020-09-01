<?php

namespace Ignite\Auth\Middleware;

use Ignite\Support\Facades\Lang;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array                    $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        $result = parent::authenticate($request, $guards);

        $this->setUserLocale();

        return $result;
    }

    /**
     * Store user locale if it is null.
     *
     * @return void
     */
    protected function setUserLocale()
    {
        if (lit_user()->locale === null) {
            lit_user()->locale = Lang::getBrowserLocale();
            lit_user()->save();
        }
    }

    /**
     * Get the path the user should be redirected to when they are not
     * authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('lit.login');
        }
    }
}
