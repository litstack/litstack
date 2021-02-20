<?php

namespace Ignite\Vue\Traits;

use Ignite\Vue\Component;

trait HasVueComponents
{
    /**
     * Vue components.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Add Vue component to stack.
     *
     * @param  \Ignite\Vue\Component|string $component
     * @return \Ignite\Vue\Component|mixed
     */
    public function component($component)
    {
        return $this->components[] = component($component);
    }

    /**
     * Prepend component to Slot.
     *
     * @param  \Ignite\Vue\Component|string $component
     * @return \Ignite\Vue\Component
     */
    public function prependComponent($component)
    {
        array_unshift(
            $this->components,
            $component = component($component)
        );

        return $component;
    }

    /**
     * Get components.
     *
     * @return void
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Determines if component for the given name is registered.
     *
     * @param  \Ignite\Vue\Component|string $name
     * @return bool
     */
    public function hasComponent($component)
    {
        $name = $component instanceof Component
            ? $component->getName()
            : $component;

        foreach ($this->components as $component) {
            if ($component->getName() == $name) {
                return true;
            }
        }

        return false;
    }
}
