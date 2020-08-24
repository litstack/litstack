<?php

namespace Lit\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Lit\Support\FacadeNeedsLitInstalled;

/**
 * @method static \Lit\Crud\FormCollection|\Lit\Crud\Models\Form load(string $collection = null, string $name = null)
 * @method static field(string $alias, string $field)
 * @method static bool fieldExists(string $alias)
 *
 * @see \Lit\Crud\Form
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
