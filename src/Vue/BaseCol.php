<?php

namespace Fjord\Vue;

use Fjord\Support\VueProp;

class BaseCol extends VueProp
{
    use Traits\HasComponent;

    /**
     * Table column attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Set value.
     *
     * @param string $value
     * @return self
     */
    public function value(string $value)
    {
        $this->attributes['value'] = $value;

        return $this;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getArray(): array
    {
        return $this->attributes;
    }
}
