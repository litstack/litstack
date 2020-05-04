<?php

namespace Fjord\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class VueProp implements Arrayable, Jsonable
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
        // When all props are passed to the Vue application, they are converted 
        // to an array using Laravel's collection method toArray. To ensure that 
        // all nested objects are converted, we convert the array to a 
        // collection instance, which is then also converted to an array.
        return collect($this->getArray());
    }

    /**
     * To json.
     *
     * @param integer $options
     * @return void
     */
    public function toJson($options = 0)
    {
        return $this->toArray()->toJson($options);
    }
}
