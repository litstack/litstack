<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;
use Fjord\Crud\Fields\Concerns\FieldHasRules;

class Code extends Field
{
    use FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-code';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
    ];

    /**
     * Available slots.
     *
     * @var array
     */
    protected $availableSlots = [];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'hint',
        'tabSize',
        'theme',
        'lineNumbers',
        'line',
        'language',
        'options',
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
        'tabSize' => 4,
        'theme' => 'default',
        'lineNumbers' => true,
        'line' => true,
        'language' => 'text/html',
        'options' => []
    ];

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
