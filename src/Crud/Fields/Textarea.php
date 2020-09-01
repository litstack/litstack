<?php

namespace Ignite\Crud\Fields;

use Ignite\Crud\BaseField;

class Textarea extends BaseField
{
    use Traits\FieldHasRules;
    use Traits\TranslatableField;
    use Traits\FieldHasPlaceholder;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-textarea';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set default textarea attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->rows(3);
        $this->maxRows(5);
    }

    /**
     * Set max characters.
     *
     * @param int $max
     *
     * @return $this
     */
    public function maxChars(int $max)
    {
        $this->setAttribute('maxChars', $max);

        return $this;
    }

    /**
     * Set rows.
     *
     * @param int $rowsrows
     *
     * @return $this
     */
    public function rows(int $rows)
    {
        $this->setAttribute('rows', $rows);

        return $this;
    }

    /**
     * Set max rows.
     *
     * @param int $rows
     *
     * @return $this
     */
    public function maxRows(int $rows)
    {
        $this->setAttribute('maxRows', $rows);

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
