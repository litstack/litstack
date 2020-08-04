<?php

namespace Fjord\Support\Facades;

use Illuminate\Support\Facades\Facade;

class FjordLang extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'fjord.translator';
    }
}
