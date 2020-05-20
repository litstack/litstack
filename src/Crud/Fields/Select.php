<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Select extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-select';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'options',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'options',
        'hint',
        'storable'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'storable' => true
    ];
}
