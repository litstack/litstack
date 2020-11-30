<?php

namespace Ignite\Contracts\Auth;

use Closure;

interface Authentication
{
    /**
     * Execute authentication.
     *
     * @param  array $credentials
     * @param  bool  $remember
     * @param  array $parameters
     * @return bool
     */
    public function attempt($credentials, bool $remember = false, array $parameters = []);

    /**
     * Add attempting closure.
     *
     * @param  Closure $closure
     * @return void
     */
    public function attempting(Closure $closure);
}
