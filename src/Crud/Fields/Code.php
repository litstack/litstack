<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Code extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-code';

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
        'tab_size',
        'theme',
        'line_numbers',
        'line',
        'language',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'tab_size' => 4,
        'theme' => 'default',
        'line_numbers' => true,
        'line' => true,
        'language' => 'text/html'
    ];
}
