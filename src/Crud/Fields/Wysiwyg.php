<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;
use Fjord\Crud\Fields\Concerns\FieldHasRules;

class Wysiwyg extends Field
{
    use FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-wysiwyg';

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
        'toolbar',
        'toolbarFormat',
        'formats',
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
        'toolbar' => [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            'blockQuote',
        ]
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
