<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Icon extends Field
{
    use Concerns\FormItemWrapper;

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
    public $requiredAttributes = [];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    public $availableAttributes = [
        'icons',
        'search'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [
        'search' => true
    ];

    /**
     * Set default icons attribute.
     *
     * @return array
     */
    public function setIconsAttribute()
    {
        return require fjord_path('src/Crud/Fields/Defaults/fontawesome_icons.php');
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
