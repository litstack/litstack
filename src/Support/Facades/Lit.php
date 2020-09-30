<?php

namespace Ignite\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string url(string $url)
 * @method static string route(string $name)
 * @method static string trans(string $key, array $replace = [], string $locale = null)
 * @method static string transChoice(string $key, int $number, array $replace = [])
 * @method static string __(string $key, array $replace = [], string $locale = null)
 * @method static string getLocale()
 * @method static \Ignite\Config\ConfigHandler|null config(string $key, ...$params)
 * @method static \Lit\Models\User|null user()
 * @method static bool installed()
 *
 * @see \Ignite\Foundation\Lit
 */
class Lit extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lit';
    }
}
