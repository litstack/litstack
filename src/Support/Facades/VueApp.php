<?php

namespace Fjord\Support\Facades;

class VueApp extends Fjord
{
    protected static function getFacadeAccessor()
    {
        return 'fjord.app.vue';
    }
}
