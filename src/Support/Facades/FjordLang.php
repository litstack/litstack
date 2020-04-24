<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FjordFacade;

class FjordLang extends FjordFacade
{
    protected static function getFacadeAccessor()
    {
        return 'translator';
    }
}
