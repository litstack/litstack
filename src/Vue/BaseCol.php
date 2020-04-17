<?php

namespace Fjord\Vue;

use Fjord\Application\Config\ConfigItem;

class BaseCol extends ConfigItem
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
