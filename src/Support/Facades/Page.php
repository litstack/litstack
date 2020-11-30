<?php

namespace Ignite\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void extend(string $key)
 * @method static array getExtensions(string $alias, string $name = null):
 *
 * @see \Ignite\Page\Factory
 */
class Page extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lit.page';
    }
}
