<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Support\VueProp;

class CrudIndex extends VueProp
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
     * CrudIndex attributes.
     *
     * @var array
     */
    protected $attributes = [];

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
     * Crud index table.
     *
     * @var CrudIndexTable
     */
    protected $table;

    /**
     * Create new CrudIndex instance.
     *
     * @param ConfigHandler $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->setDefaults();
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    public function setDefaults()
    {
        $this->expand(false);
    }

    /**
     * Get CrudIndex table.
     *
     * @return void
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Expand CrudIndex container.
     *
     * @param boolean $expand
     * @return $this
     */
    public function expand(bool $expand = true)
    {
        $this->attributes['expand'] = $expand;

        return $this;
    }

    /**
     * Create CrudIndex table.
     *
     * @param Closure $closure
     * @return $this
     */
    public function table(Closure $closure)
    {
        $table = new CrudIndexTable($this->config);

        $this->table = $table;

        $closure($table->getTable());

        $this->component($table->getComponent())
            ->prop('table', $table);

        return $table;
    }

    /**
     * Add CrudIndex container slot.
     *
     * @param string $name
     * @param [type] $value
     * @return void
     */
    public function slot(string $name, $value)
    {
        $this->slots[$name] = $value;
    }

    /**
     * Is registering component in wrapper.
     *
     * @return boolean
     */
    public function inWrapper()
    {
        return $this->wrapper != null;
    }

    /**
     * Get current card.
     *
     * @return array $card
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Add Vue component field.
     *
     * @param \Fjord\Vue\Component|string $component
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
     * Get new wrapper
     * 
     * @param string|Component $component
     * @return component
     */
    protected function getNewWrapper($component)
    {
        if (is_string($component)) {
            $component = component($component);
        }

        $wrapper = $this->component('fj-field-wrapper');

        return $wrapper->wrapperComponent($component);
    }

    /**
     * Create wrapper.
     *
     * @param string|Component $component
     * @param Closure $closure
     * @return self
     */
    public function wrapper($component, Closure $closure)
    {
        $newWrapper = $this->getNewWrapper($component);

        return $this->registerWrapper($newWrapper, $closure);
    }

    /**
     * Register new wrapper.
     * 
     * @return Component
     */
    public function registerWrapper($wrapper, $closure)
    {
        if ($this->inWrapper()) {

            $this->wrapper
                ->component($wrapper);

            $this->wrapperStack[] = $this->wrapper;
        }

        $this->wrapper = $wrapper;
        $closure($this);
        $this->wrapper = !empty($this->wrapperStack)
            ? array_pop($this->wrapperStack)
            : null;

        return $wrapper->wrapperComponent;
    }

    /**
     * Set attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get attribute.
     *
     * @param string $name
     * @return void
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
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
            'slots' => collect($this->slots),
        ], $this->attributes);
    }
}
