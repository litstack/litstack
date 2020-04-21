<?php

namespace Fjord\Application\Vue;

class Components
{
    /**
     * Registered components.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Register component.
     *
     * @param string $name
     * @param string $class
     * @return void
     */
    public function register(string $name, string $class)
    {
        $this->components[$name] = $class;
    }

    /**
     * Is component name registered.
     *
     * @param string $name
     * @return boolean
     */
    public function isRegistered(string $name)
    {
        return array_key_exists($name, $this->components);
    }

    /**
     * Get new component instance.
     *
     * @param string $name
     * @return void
     */
    public function component(string $name)
    {
        return new $this->components[$name]($name);
    }
}
