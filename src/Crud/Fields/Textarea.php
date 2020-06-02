<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;

class Textarea extends BaseField
{
    use Traits\FieldHasRules,
        Traits\TranslatableField,
        Traits\FieldHasPlaceholder;

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
     * Set default textarea attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->rows(3);
        $this->maxRows(5);
    }


    /**
     * Set max characters.
     *
     * @param integer $max
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
     * @param integer $rowsrows
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
     * @param integer $rows
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
     * @return boolean
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
