<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FjordFacade;

/**
 * @method static array names(string $model)
 *
 * @see \Fjord\Crud\Crud
 */
class Crud extends FjordFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'crud';
    }
}
