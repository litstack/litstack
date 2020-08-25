<?php

namespace Ignite\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Lang extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lit.translator';
    }
}
