<?php

namespace Fjord\Vue\Container;

use Closure;
use Fjord\Support\HasAttributes;
use Fjord\Support\VueProp;
use InvalidArgumentException;

abstract class Container extends VueProp
{
    use HasAttributes;

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
     * CrudIndex container components.
     *
     * @var array
     */
    protected $components = [];

    /**
     * CrudIndex container slots.
     *
     * @var array
     */
    protected $slots = [];

    /**
     * Add Vue component to stack.
     *
     * @param  \Fjord\Vue\Component|string $component
     * @return \Fjord\Vue\Component
     */
    public function component($component)
    {
        if (is_string($component)) {
            $component = component($component);
        }

        if ($this->inWrapper()) {
            $this->wrapper->component($component);
        } else {
            $this->components[] = $component;
        }

        return $component;
    }

    /**
     * Add container slot.
     *
     * @param  string $name
     * @param  string $value
     * @return void
     */
    public function slot(string $name, $value)
    {
        $this->slots[$name] = $value;
    }

    /**
     * Create wrapper.
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
        $closure($this);
        $this->wrapper = ! empty($this->wrapperStack)
            ? array_pop($this->wrapperStack)
            : null;

        return $wrapper->wrapperComponent;
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

        return $this->component('fj-field-wrapper')
            ->wrapperComponent($component);
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
     * Render CrudIndex container for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge([
            'components' => collect($this->components),
            'slots'      => collect($this->slots),
        ], $this->attributes);
    }

    /**
     * Get attribute by name.
     *
     * @param  string                   $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        if ($this->hasAttribute($name)) {
            return $this->getAttribute($name);
        }

        throw new InvalidArgumentException("Property [{$name}] does not exist on " . static::class);
    }

    /**
     * Set attribute value.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return void
     */
    public function __set($attribute, $value)
    {
        if ($this->hasAttribute($attribute)) {
            $this->attribute[$attribute] = $value;

            return;
        }

        $this->{$attribute} = $value;
    }
}
