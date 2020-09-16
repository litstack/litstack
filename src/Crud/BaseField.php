<?php

namespace Ignite\Crud;

use Ignite\Crud\Fields\Traits\HasBaseField;

class BaseField extends Field
{
    use HasBaseField;

    /**
     * Set default title.
     *
     * @return string
     */
    protected function setTitleDefault()
    {
        return ucfirst($this->id);
    }
}
