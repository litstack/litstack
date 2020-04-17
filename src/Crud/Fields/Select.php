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
    protected $component = 'fj-form-select';

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
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [];
}
