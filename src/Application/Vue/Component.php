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
     * Pass props to extension that should be edited.
     * 
     * @return array props
     */
    abstract public function passToExtension();

    /**
     * Receive props from extension and bind them to component. 
     *
     * @param $props
     * @return void
     */
    abstract public function receiveFromExtension($props);

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
