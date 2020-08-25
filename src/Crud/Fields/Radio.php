<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;

class Radio extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-radio';

    /**
     * Required attributes.
     *
     * @var array
     */
    public $required = ['options'];

    /**
     * Set select options.
     *
     * @param array $options
     *
     * @return self
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
}
