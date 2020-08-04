<?php

namespace Fjord\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Package extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'fjord.packages';
    }
}
