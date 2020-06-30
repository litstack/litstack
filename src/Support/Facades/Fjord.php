<?php

namespace Fjord\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string url(string $url)
 * @method static string route(string $name)
 * @method static string trans(string $key, $replace = [])
 *
 * @see \Fjord\Fjord\Fjord
 */
class Fjord extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fjord';
    }
}
