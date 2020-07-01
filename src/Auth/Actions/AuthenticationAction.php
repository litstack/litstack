<?php

namespace Fjord\Auth\Actions;

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
        if (Auth::guard('fjord')->attempt($credentials, $remember)) {
            return true;
        }

        if (!config('fjord.login.username')) {
            return false;
        }

        $credentials['username'] = $credentials['email'];
        unset($credentials['email']);

        return Auth::guard('fjord')->attempt($credentials, $remember);
    }
}
