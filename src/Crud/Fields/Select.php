<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;

class Select extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-select';

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
     * Set select palceholder.
     *
     * @param string $placeholder
     * @return self
     */
    public function placeholder(string $placeholder)
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }
}
