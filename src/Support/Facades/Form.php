<?php

namespace Ignite\Support\Facades;

use Ignite\Support\FacadeNeedsLitInstalled;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ignite\Crud\FormCollection|\Ignite\Crud\Models\Form load(string $collection = null, string $name = null)
 * @method static field(string $alias, string $field)
 * @method static bool fieldExists(string $alias)
 *
 * @see \Ignite\Crud\Form
 */
class Form extends Facade
{
    use FacadeNeedsLitInstalled;

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lit.form';
    }
}
