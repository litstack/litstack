<?php

namespace Lit\Crud\Fields;

use Lit\Crud\BaseField;

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
}
