<?php

namespace Ignite\Auth\Middleware;

use Ignite\Support\Facades\Lang;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

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

        if (! $this->isUserAuthorized($request)) {
            $this->unauthenticated($request, $guards);
        }

        $this->setUserLocale();

        return $result;
    }

    /**
     * Determine if the authenticated user is authorized to view the litstack
     * interface.
     *
     * @param  Request $request
     * @return bool
     */
    protected function isUserAuthorized(Request $request)
    {
        return lit()->authorized();
    }

    /**
     * Store user locale if it is null.
     *
     * @return void
     */
    protected function setUserLocale()
    {
        if (lit_user()->locale !== null) {
            return;
        }

        if (! in_array('locale', lit_user()->getFillable())) {
            return;
        }

        lit_user()->update([
            'locale' => Lang::getBrowserLocale(),
        ]);
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array                    $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        if (! lit_user()) {
            parent::unauthenticated($request, $guards);
        }

        (new Response(false, 'Forbidden', 403))->authorize();
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
