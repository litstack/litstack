<?php

namespace Ignite\Page\Concerns;

use Closure;

trait ManagesWrapper
{
    /**
     * Current wrapper component.
     *
     * @var Component
     */
    protected $wrapper;

    /**
     * Wrapper component stack.
     *
     * @var array
     */
    protected $wrapperStack = [];

    /**
     * Create a wrapper.
     *
     * @param  string|Component $component
     * @param  Closure          $closure
     * @return self
     */
    public function wrapper($component, Closure $closure)
    {
        $wrapper = $this->getNewWrapper($component);

        if ($this->inWrapper()) {
            $this->wrapperStack[] = $this->wrapper;
        }

        // Set current wrapper.
        $this->wrapper = $wrapper;
        $closure($this, $wrapper);
        $this->wrapper = ! empty($this->wrapperStack)
            ? array_pop($this->wrapperStack)
            : null;

        return $wrapper->wrapperComponent;
    }

    /**
     * Determines if currently in wrapper.
     *
     * @return bool
     */
    public function inWrapper()
    {
        return $this->wrapper !== null;
    }

    /**
     * Get current wrapper.
     *
     * @return Component $wrapper
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Get new wrapper.
     *
     * @param  string|Component $component
     * @return component
     */
    protected function getNewWrapper($component)
    {
        if (is_string($component)) {
            $component = component($component);
        }

        return $this->component('lit-field-wrapper')
            ->wrapperComponent($component);
    }
}
