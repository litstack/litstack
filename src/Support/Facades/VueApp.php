<?php

namespace Ignite\Support\Facades;

class VueApp extends Lit
{
    protected static function getFacadeAccessor()
    {
        return 'lit.vue.app';
    }
}
