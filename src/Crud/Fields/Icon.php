<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Icon extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-icon';

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
        'icons',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Set default attributes.
     *
     * @return void
     */
    protected function setDefaults()
    {
        parent::setDefaults();

        $this->attributes['icons'] = require fjord_path('src/Crud/Fields/defaults/fontawesome_icons.php');
    }

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
