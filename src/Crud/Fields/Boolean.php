<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;

class Boolean extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-boolean';

    /**
     * Cast field value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function castValue($value)
    {
        return (bool) $value;
    }
}
