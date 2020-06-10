<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;

class Input extends BaseField
{
    use Traits\FieldHasRules,
        Traits\TranslatableField,
        Traits\FieldHasPlaceholder;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-input';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set max chars.
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
     * Set default attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->type('text');
    }

    /**
     * Set append.
     *
     * @param string $prepend
     * @return $this
     */
    public function append(string $append)
    {
        $this->setAttribute('append', $append);

        return $this;
    }

    /**
     * Set prepend.
     *
     * @param string $prepend
     * @return $this
     */
    public function prepend(string $prepend)
    {
        $this->setAttribute('prepend', $prepend);

        return $this;
    }

    /**
     * Set input type.
     *
     * @param string $type
     * @return $this
     */
    public function type(string $type)
    {
        $this->setAttribute('type', $type);

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
