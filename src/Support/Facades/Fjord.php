<?php

namespace Fjord\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string url(string $url)
 * @method static string route(string $name)
 * @method static string trans(string $key, array $replace = [], string $locale = null)
 * @method static string trans_choice(string $key, int $number, array $replace = [])
 * @method static string __(string $key, array $replace = [], string $locale = null)
 * @method static string getLocale()
 * @method static \Fjord\Config\ConfigHandler|null config(string $key, ...$params)
 * @method static \Fjord\User\Models\FjordUser|null user()
 * @method static bool installed()
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
