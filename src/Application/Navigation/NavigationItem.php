<?php

namespace Ignite\Application\Navigation;

use ArrayAccess;
use Ignite\Support\HasAttributes;
use Ignite\Support\VueProp;

abstract class NavigationItem extends VueProp implements ArrayAccess
{
    use HasAttributes;

    /**
     * Set an attribute.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    /**
     * Determine if an attribute value exists.
     *
     * @param  string $key
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Remove an attribute.
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Retrieve an attribute by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->attributes[$offset])
            ? $this->attributes[$offset]
            : null;
    }

    public function __get($key)
    {
        if ($this->hasAttribute($key)) {
            return $this->attributes[$key];
        }
    }
}
