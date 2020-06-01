<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Textarea extends Field
{
    use Concerns\FieldHasRules,
        Concerns\TranslatableField,
        Concerns\FormItemWrapper,
        Concerns\FieldHasPlaceholder;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-textarea';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];


    /**
     * Set max characters.
     *
     * @param integer $max
     * @return $this
     */
    public function max(int $max)
    {
        $this->setAttribute('max', $max);

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
