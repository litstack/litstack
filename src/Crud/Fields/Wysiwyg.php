<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Wysiwyg extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-wysiwyg';

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
        'title',
        'placeholder',
        'hint',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [];
}
