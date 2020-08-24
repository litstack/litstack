<?php

namespace Lit\Support\Facades;

use Illuminate\Support\Facades\Facade;

class LitRoute extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lit.router';
    }
}
