<?php

namespace Ignite\Support;

trait HasAttributes
{
    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Set attribute value.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Merge or set attribute value.
     *
     * @param  string $name
     * @param  array  $value
     * @return void
     */
    public function mergeOrSetAttribute(string $name, array $value)
    {
        if (! $this->hasAttribute($name)) {
            $this->setAttribute($name, $value);
        }

        $this->attributes[$name] = array_merge($this->attributes[$name], $value);
    }

    /**
     * Get attribute by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Determines if attribute with the given name exists.
     *
     * @param  string $name
     * @return bool
     */
    public function hasAttribute(string $name)
    {
        return array_key_exists($name, $this->attributes);
    }
}
