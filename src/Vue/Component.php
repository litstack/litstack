<?php

namespace Ignite\Vue;

use Exception;
use Ignite\Contracts\Vue\Authorizable as AuthorizableContract;
use Ignite\Crud\FieldDependency;
use Ignite\Support\VueProp;
use Ignite\Vue\Traits\Authorizable;

class Component extends VueProp implements AuthorizableContract
{
    use Authorizable;

    /**
     * Vue component name.
     *
     * @var string
     */
    protected $name;

    /**
     * Vue component props.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Component classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Event handlers.
     *
     * @var array
     */
    protected $events = [];

    /**
     * The name of the slot, if this component is the child of another component.
     *
     * @var array
     */
    protected $slot = [];

    /**
     * Children.
     *
     * @var array
     */
    protected $children = [];

    /**
     * DOM properties.
     *
     * @var array
     */
    protected $domProps = [];

    /**
     * Rendering dependencies.
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * The object that the component dependencies are linked to.
     *
     * @var string
     */
    protected $dependor = 'model';

    /**
     * Create new Component instance.
     *
     * @param  string $name
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->beforeMount();
    }

    /**
     * Before mount lifecycle hook.
     *
     * @return void
     */
    protected function beforeMount()
    {
        //
    }

    /**
     * Mounted lifecycle hook.
     *
     * @return void
     */
    protected function mounted()
    {
        //
    }

    /**
     * Rendered lifecycle hook.
     *
     * @param  array $rendered
     * @return void
     */
    protected function rendered($rendered)
    {
        //
    }

    /**
     * Add single prop.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return self
     */
    public function prop(string $name, $value = true)
    {
        $this->props[$name] = $value;

        return $this;
    }

    /**
     * Bind multiple props.
     *
     * @param  array $props
     * @return self
     */
    public function bind(array $props)
    {
        foreach ($props as $name => $value) {
            $this->prop($name, $value);
        }

        return $this;
    }

    /**
     * Set dom prop.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return string
     */
    public function domProp($name, $value)
    {
        $this->domProps[$name] = $value;

        return $this;
    }

    /**
     * Get dom prop.
     *
     * @param  string $name
     * @return mixed
     */
    public function getDomProp($name)
    {
        return $this->domProps[$name] ?? null;
    }

    /**
     * The name of the slot, if this component is the child of another component.
     *
     * @param  string $name
     * @return $this
     */
    public function slot($name)
    {
        $this->slot = $name;

        return $this;
    }

    /**
     * Gets slot name.
     *
     * @return string|null
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Add child component.
     *
     * @param  mixed $component
     * @return $this
     */
    public function child($component)
    {
        $this->children[] = $component;

        return $this;
    }

    /**
     * Get children.
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add content.
     *
     * @param  string $content
     * @return $this
     */
    public function content($content)
    {
        return $this->child($content);
    }

    /**
     * Add event handler.
     *
     * @param  string $event
     * @param  string $handler
     * @return $this
     */
    public function on($event, $handler)
    {
        $this->events[$event] = $handler;

        return $this;
    }

    /**
     * Get the component events.
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Gets event handler.
     *
     * @param  string      $event
     * @return string|null
     */
    public function getEventHandler($event)
    {
        return $this->events[$event];
    }

    /**
     * Set component class.
     *
     * @param  string $value
     * @return void
     */
    public function class(string $class)
    {
        if (! in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }

        return $this;
    }

    /**
     * Get classes.
     *
     * @return void
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Get component name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get props.
     *
     * @return array
     */
    public function getProps()
    {
        return $this->props;
    }

    /**
     * Get prop by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function getProp($name)
    {
        return $this->props[$name] ?? null;
    }

    /**
     * Check's if a prop has been set.
     *
     * @return bool
     */
    public function hasProp($name)
    {
        return array_key_exists($name, $this->props);
    }

    /**
     * Get component attribute.
     *
     * @param  string $name
     * @return void
     */
    public function __get(string $name)
    {
        if ($this->hasProp($name)) {
            return $this->getProp($name);
        }

        return $this->{$name};
    }

    /**
     * Get missing props.
     *
     * @return array
     */
    protected function getMissing()
    {
        return [];
    }

    /**
     * Check for missing required props.
     *
     * @throws \Exception
     * @return bool
     */
    public function checkComplete()
    {
        if (empty($missing = $this->getMissing())) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required props: [%s] for component "%s"',
            implode(', ', $missing),
            $this->name
        ));
    }

    /**
     * Add dependency.
     *
     * @param  FieldDependency $dependency
     * @return $this
     */
    public function addDependency(FieldDependency $dependency)
    {
        $this->dependencies[] = $dependency;

        return $this;
    }

    /**
     * Set the object name that the component depends on.
     *
     * @param  string $dependor
     * @return $this
     */
    public function dependsOn($dependor)
    {
        $this->dependor = $dependor;

        return $this;
    }

    /**
     * Get dependencies.
     *
     * @return string
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function render(): array
    {
        $this->checkComplete();

        $this->mounted();

        $rendered = [
            'name'         => $this->name,
            'props'        => collect($this->props),
            'domProps'     => $this->domProps,
            'events'       => $this->events,
            'slot'         => $this->slot,
            'children'     => $this->children,
            'classes'      => $this->classes,
            'dependencies' => $this->dependencies,
        ];

        $this->rendered($rendered);

        return $rendered;
    }

    /**
     * Call component method.
     *
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, array $parameters)
    {
        if (FieldDependency::conditionExists($method)) {
            $dependency = FieldDependency::make($method, ...$parameters);
            $dependency->setDependor($this->dependor);

            return $this->addDependency($dependency);
        }
    }
}
