<?php

namespace Fjord\Application\Config;

use Illuminate\Contracts\Support\Arrayable;

abstract class ConfigItem implements Arrayable
{
    /**
     * Return array that should be passed to Vue.
     *
     * @return array
     */
    abstract protected function getArray(): array;

    /**
     * Get attributes.
     *
     * @return \Illuminate\Support\Collection $attributes
     */
    public function toArray()
    {
        // The collection instance calls the toArray method for each array 
        // attribute. This also converts all the attributes in it.
        return collect($this->getArray());
    }
}
