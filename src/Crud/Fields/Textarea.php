<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Textarea extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-textarea';

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
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'max' => 250,
    ];
}
