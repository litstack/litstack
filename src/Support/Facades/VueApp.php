<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FjordFacade;

class VueApp extends FjordFacade
{
    protected static function getFacadeAccessor()
    {
        return 'vue';
    }
}
