<?php

namespace Fjord\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fjord\Vue\Vuew
 */
class Vue extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fjord.vue';
    }
}
