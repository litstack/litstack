<?php

namespace Ignite\Crud\Fields\Traits;

use Closure;

trait FieldHasForm
{
    /**
     * Add form.
     *
     * @param Closure $closure
     *
     * @return this
     */
    abstract public function form(Closure $closure);

    /**
     * Get registered fields.
     *
     * @return array
     */
    public function getRegisteredFields()
    {
        if (! array_key_exists('form', $this->attributes)) {
            return collect([]);
        }

        return $this->attributes['form']->getRegisteredFields();
    }
}
