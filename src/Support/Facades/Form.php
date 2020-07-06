<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FacadeNeedsFjordsInstalled;
use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    use FacadeNeedsFjordsInstalled;

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fjord.form';
    }
}
