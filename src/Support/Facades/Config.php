<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FjordFacade;

/**
 * @property static get(string $key): mixed
 * @property static exists(string $key): boolean
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
