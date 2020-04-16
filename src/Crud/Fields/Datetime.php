<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Datetime extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-date-time';

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
        'title',
        'hint',
        'formatted',
        'inline',
        'only_date'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'formatted' => 'llll',
        'no_label' => false,
        'inline' => false,
        'only_date' => true
    ];
}
