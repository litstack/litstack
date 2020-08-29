<?php

namespace Ignite\Vue;

use Closure;
use Ignite\Contracts\Vue\Vue as VueContract;

class Vue implements VueContract
{
    /**
     * Registered components.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Regsiter component for the given name.
     *
     * @param  string         $name
     * @param  Closure|string $component
     * @return $this
     */
    public function component($name, $component)
    {
        if (is_string($component)) {
            $component = fn ($name) => new $component($name);
        }

        $this->components[$name] = $component;

        return $this;
    }

    /**
     * Determines if a component is registered.
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->components);
    }

    /**
     * Create component instance from name.
     *
     * @param  string          $name
     * @return Component|mixed
     */
    public function make($name)
    {
        if ($this->has($name)) {
            return $this->components[$name]($name);
        }

        return new Component($name);
    }

    /**
     * Get all registered vue components.
     *
     * @return array
     */
    public function all()
    {
        return $this->components;
    }
}
