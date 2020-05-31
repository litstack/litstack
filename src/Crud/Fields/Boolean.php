<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Boolean extends Field
{
    use Concerns\FormItemWrapper;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-boolean';

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        return (bool) $value;
    }
}
