<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Field;

class Select extends Field
{
    use Concerns\FieldHasRules,
        Concerns\FormItemWrapper;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-select';

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
     * @return self
     */
    public function options(array $options)
    {
        $this->setAttribute('options', $options);

        return $this;
    }
}
