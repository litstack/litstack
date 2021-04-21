<?php

namespace Ignite\Support;

use Illuminate\Contracts\Support\Arrayable;

class AttributeBag implements Arrayable
{
    use HasAttributes;

    /**
     * Create new AttributeBag instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Determines if bag has attribute with the given name.
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return $this->hasAttribute($name);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Get attribute by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }
}
