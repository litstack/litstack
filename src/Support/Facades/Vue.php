<?php

namespace Ignite\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ignite\Vue\Vuew
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
        return 'lit.vue';
    }
}
