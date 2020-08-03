<?php

namespace Fjord\Support\Facades;

use Fjord\Support\FacadeNeedsFjordsInstalled;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Fjord\Crud\FormFieldCollection|\Fjord\Crud\Models\FormField load(string $collection = null, string $name = null)
 * @method static field(string $alias, string $field)
 * @method static bool fieldExists(string $alias)
 *
 * @see \Fjord\Crud\Form
 */
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
