<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;
use Fjord\Crud\Fields\Concerns\FieldHasRules;

class Input extends Field
{
    use FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-input';

    /**
     * Is field translatable.
     *
     * @var boolean
     */
    protected $translatable = true;

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'max',
        'title',
        'placeholder',
        'hint',
        'type',
        'prepend',
        'append',
        'rules',
        'updateRules',
        'creationRules',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
