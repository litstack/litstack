<?php

namespace Ignite\Page\Concerns;

use Closure;
use Ignite\Page\Wrapper\CardWrapper;
use Ignite\Page\Wrapper\CardWrapperComponent;
use Ignite\Page\Wrapper\WrapperComponent;

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

        return $this->component(WrapperComponent::class)
            ->wrapperComponent($component);
    }

    /**
     * Create b-card wrapper.
     *
     * @param  int         $cols
     * @param  Closure     $closure
     * @return CardWrapper
     */
    public function card(Closure $closure)
    {
        return $this->wrapper(new CardWrapperComponent, fn ($form) => $closure($this))
            ->class('mb-4');
    }
}
