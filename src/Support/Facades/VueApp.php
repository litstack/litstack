<?php

namespace Fjord\Support\Facades;

class VueApp extends Fjord
{
    protected static function getFacadeAccessor()
    {
        return 'fjord.vue.app';
    }
}
