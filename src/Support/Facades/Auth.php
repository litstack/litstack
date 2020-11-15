<?php

namespace Ignite\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool attempt(string $key)
 *
 * @see \Ignite\Auth\Authentication
 */
class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lit.auth';
    }
}
