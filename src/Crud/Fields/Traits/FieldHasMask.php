<?php

namespace Ignite\Crud\Fields\Traits;

trait FieldHasMask
{
    /**
     * Set mask.
     *
     * @param  array $mask
     * @return $this
     */
    public function mask(array $mask)
    {
        $this->setAttribute('mask', $mask);

        return $this;
    }
}
