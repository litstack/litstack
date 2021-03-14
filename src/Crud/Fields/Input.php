<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;

class Input extends BaseField
{
    use Traits\FieldHasMask,
        Traits\FieldHasRules,
        Traits\TranslatableField,
        Traits\FieldHasPlaceholder;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-input';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set max chars.
     *
     * @param  int   $max
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
    public function mount()
    {
        $this->type('text');
    }

    /**
     * Set append.
     *
     * @param string $prepend
     *
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
     *
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
     *
     * @return $this
     */
    public function type(string $type)
    {
        $this->setAttribute('type', $type);

        return $this;
    }

    /**
     * Enable autofocus.
     *
     * @param  bool $focus
     * @return void
     */
    public function autofocus($focus = true)
    {
        $this->setAttribute('autofocus', $focus);

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
        return (string) $value;
    }
}
