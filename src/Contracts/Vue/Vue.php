<?php

namespace Ignite\Contracts\Vue;

interface Vue
{
    /**
     * Regsiter component for the given name.
     *
     * @param  string         $name
     * @param  Closure|string $component
     * @return $this
     */
    public function component($name, $component);

    /**
     * Determines if a component is registered.
     *
     * @param  string $name
     * @return bool
     */
    public function has($name);

    /**
     * Create component instance from name.
     *
     * @param  string          $name
     * @return Component|mixed
     */
    public function make($name);
}
