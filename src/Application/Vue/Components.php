<?php

namespace Fjord\Application\Vue;

use Fjord\Vue\Component;

class Components
{
    /**
     * Registered component classes.
     *
     * @var array
     */
    protected $componentClasses = [];

    /**
     * Registered component classes.
     *
     * @var array
     */
    protected $componentArrays = [];

    /**
     * Register component.
     *
     * @param string $name
     * @param string|array $class
     * @return void
     */
    public function register(string $name, $class)
    {
        if (is_array($class)) {
            $this->componentArrays[$name] = $class;

            return;
        }

        $this->componentClasses[$name] = $class;
    }

    /**
     * Get all components.
     *
     * @return array
     */
    public function all()
    {
        return array_merge($this->componentClasses, $this->componentArrays);
    }

    /**
     * Is component name registered.
     *
     * @param string $name
     * @return boolean
     */
    public function isRegistered(string $name)
    {
        return array_key_exists($name, $this->componentClasses)
            || array_key_exists($name, $this->componentArrays);
    }

    /**
     * Get new component instance.
     *
     * @param string $name
     * @return void
     */
    public function component(string $name)
    {
        if (array_key_exists($name, $this->componentClasses)) {
            return new $this->componentClasses[$name]($name);
        }

        return new Component($name, $this->componentArrays[$name]);
    }
}
