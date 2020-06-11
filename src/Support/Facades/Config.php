<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FjordFacade;

/**
 * @method static \Fjord\Config\ConfigHandler get(string $key)
 * @method static bool exists(string $key): 
 * 
 * @see \Fjord\Config\ConfigLoader
 */
class Config extends FjordFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'config.loader';
    }
}
