<?php

namespace Ignite\Crud\Fields\Traits;

trait FieldHasPlaceholder
{
    /**
     * Set placeholder.
     *
     * @param string $placeholder
     *
     * @return $this
     */
    public function placeholder(string $placeholder)
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }
}
