<?php

namespace Fjord\Vue\Components;

abstract class BaseComponent
{
    /**
     * Available component properties.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Check if component has prop.
     *
     * @param string $name
     * @return boolean
     */
    public function hasProp(string $name)
    {
        return in_array($name, $this->props);
    }

    /**
     * Get props.
     *
     * @return array
     */
    public function getProps()
    {
        return $this->props;
    }
}
