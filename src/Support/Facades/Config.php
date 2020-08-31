<?php

namespace Ignite\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ignite\Config\ConfigHandler|null get(string $key)
 * @method static bool exists(string $key):
 *
 * @see \Ignite\Config\ConfigLoader
 */
class Config extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lit.config';
    }
}
