<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;
use Fjord\Crud\Fields\Concerns\FieldHasRules;

class Select extends Field
{
    use FieldHasRules;

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
        'storable',
        'rules',
        'updateRules',
        'creationRules',
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
