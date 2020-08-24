<?php

namespace Lit\Support\Facades;

use Illuminate\Support\Facades\Facade;

class LitLang extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lit.translator';
    }
}
