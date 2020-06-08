<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\BaseField;

class ListField extends BaseField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-list';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->search(true);
    }

    /**
     * Set search.
     *
     * @param boolean $search
     * @return self
     */
    public function search(bool $search = true)
    {
        $this->setAttribute('search', $search);

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
