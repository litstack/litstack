<?php

namespace Fjord\Support\Facades;

class VueApp extends FjordFacade
{
    protected static function getFacadeAccessor()
    {
        return 'vue';
    }
}
