<?php

namespace Ignite\Crud\Fields;

use Closure;
use Ignite\Crud\BaseField;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Fields\Traits\TranslatableField;

class Listing extends BaseField
{
    use TranslatableField;

    /**
     * Vue component name.
     *
     * @var string
     */
    protected $component = 'lit-field-listing';

    public function __construct(string $id, Closure $closure)
    {
        parent::__construct($id);

        $form = new BaseForm('');
        $form->setRoutePrefix('');

        $closure($form);

        $this->setAttribute('fields', $form->getRegisteredFields());
    }

    /**
     * Set field defaults.
     *
     * @return void
     */
    public function mount()
    {
    }

    public function sameNumberOfRowsForAllLocales($same)
    {
        $this->setAttribute('equal_rows', $same);
    }

    /**
     * Cast field value.
     *
     * @param  mixed $value
     * @return bool
     */
    public function castValue($value)
    {
        if (is_null($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        return json_decode($value, 0);
    }
}
