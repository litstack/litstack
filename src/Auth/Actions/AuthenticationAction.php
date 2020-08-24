<?php

namespace Lit\Auth\Actions;

use Illuminate\Support\Facades\Auth;

class AuthenticationAction
{
    /**
     * Execute authentication.
     *
     * @param array $credentials
     * @param bool  $remember
     *
     * @return bool
     */
    public function execute($credentials, bool $remember = false)
    {
        if (Auth::guard('lit')->attempt($credentials, $remember)) {
            return true;
        }

        if (! config('lit.login.username')) {
            return false;
        }

        $credentials['username'] = $credentials['email'];
        unset($credentials['email']);

        return Auth::guard('lit')->attempt($credentials, $remember);
    }
}
