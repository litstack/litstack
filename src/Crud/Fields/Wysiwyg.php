<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;

class Wysiwyg extends BaseField
{
    use Traits\FieldHasRules,
        Traits\TranslatableField;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-wysiwyg';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        //
    }

    /**
     * Set font colors
     *
     * @param array $colors
     * @return void
     */
    public function colors(array $colors)
    {
        $this->setAttribute('colors', $colors);

        return $this;
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
