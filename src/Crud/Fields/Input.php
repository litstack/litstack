<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Input extends Field
{
    use Concerns\FieldHasRules,
        Concerns\TranslatableField,
        Concerns\FormItemWrapper;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-input';

    /**
     * Required attributes.
     *
     * @var array
     */
    public $requiredAttributes = [];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    public $availableAttributes = [
        'max',
        'placeholder',
        'type',
        'prepend',
        'append',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [];

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
