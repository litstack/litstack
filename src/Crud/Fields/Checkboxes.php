<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;

class Checkboxes extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-checkboxes';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['options'];

    /**
     * Set options.
     *
     * @param array $options
     * @return $this
     */
    public function options(array $options)
    {
        $this->setAttribute('options', $options);

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
        if (is_array($value)) {
            return $value;
        }

        return json_decode($value, 0);
    }
}
