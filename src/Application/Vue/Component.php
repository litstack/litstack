<?php

namespace AwStudio\Fjord\Application\Vue;

abstract class Component
{
    /**
     * Vue component name.
     * 
     * @var string
     */
    protected $name;

    /**
     * List of props that are passed to the Vue component.
     * 
     * @var array
     */
    protected $props;

    /**
     * Create component instance.
     * 
     * @param string $name
     * @param array $props
     * @return void
     */
    public function __construct(string $name, $props)
    {
        $this->name = $name;
        $this->props = $props;
    }

    /**
     * Add prop to Vue component.
     *
     * @param string $name
     * @param $value
     * @return void
     */
    public function addProp(string $name, $value)
    {
        // TODO: Throw exception if exists.
        $this->props[$name] = $value;
    }

    /**
     * Get component name.
     * 
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get component props.
     *
     * @return array $props
     */
    public function getProps()
    {
        return $this->props;
    }
}
