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
     * Compare original props to extended props and do changes if needed.
     * 
     * @param array $original
     * @param array $extended
     * @return array props
     */
    abstract public function handleExtension(array $original, array $extended);

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
}