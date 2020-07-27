<?php

namespace Fjord\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Fjord\Config\ConfigHandler get(string $key)
 * @method static bool exists(string $key):
 *
 * @see \Fjord\Config\ConfigLoader
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
        return 'fjord.app.config.loader';
    }
}
