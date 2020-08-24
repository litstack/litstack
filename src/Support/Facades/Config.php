<?php

namespace Lit\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Lit\Config\ConfigHandler|null get(string $key)
 * @method static bool exists(string $key):
 *
 * @see \Lit\Config\ConfigLoader
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
        return 'lit.app.config.loader';
    }
}
