<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Range extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-range';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'min',
        'max'
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'hint',
        'step',
        'min',
        'max',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'step' => 1,
    ];

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        return (int) $value;
    }
}
