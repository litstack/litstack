<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FjordFacade;

class Package extends FjordFacade
{
    public static function getFacadeAccessor()
    {
        return 'packages';
    }
}
