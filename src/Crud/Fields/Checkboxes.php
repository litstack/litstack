<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;

class Checkboxes extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-checkboxes';

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
     *
     * @return $this
     */
    public function options(array $options)
    {
        $this->setAttribute('options', $options);

        return $this;
    }

    /**
     * Set stacked.
     *
     * @param  bool  $stacked
     * @return $this
     */
    public function stacked($stacked = true)
    {
        $this->setAttribute('stacked', $stacked);

        return $this;
    }

    /**
     * Cast field value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function castValue($value)
    {
        if (is_array($value)) {
            return $value;
        }

        return json_decode($value, 0);
    }
}
