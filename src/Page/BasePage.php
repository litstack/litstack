<?php

namespace Fjord\Page;

use Fjord\Contracts\Page\Page;
use Fjord\Support\HasAttributes;
use InvalidArgumentException;

abstract class BasePage implements Page
{
    use HasAttributes,
        Concerns\ManagesWrapper;

    /**
     * CrudIndex container components.
     *
     * @var array
     */
    protected $components = [];

    /**
     * Props that should be bound to child Vue components.
     *
     * @var array
     */
    protected $props = [];

    /**
     * List of Blade Views.
     *
     * @var array
     */
    protected $views = [];

    /**
     * View data.
     *
     * @var array
     */
    protected $viewData = [];

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
     * Get components.
     *
     * @return void
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Bind view to page.
     *
     * @param  string|View $view
     * @return void
     */
    public function view($view)
    {
        if (! $view instanceof View) {
            $view = view($view);
        }

        $this->views[] = $view;

        $this->component('fj-blade')->prop('view', $view);

        return $view;
    }

    /**
     * Bind data to Vue components and Blade views.
     *
     * @param  array $data
     * @return $this
     */
    public function bind(array $data)
    {
        $this->bindToView($data);
        $this->bindToVue($data);

        return $this;
    }

    /**
     * Bind data to view.
     *
     * @param  array $data
     * @return void
     */
    public function bindToView(array $data)
    {
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    /**
     * Bind props to page child Vue components.
     *
     * @param  array $props
     * @return $this
     */
    public function bindToVue(array $props)
    {
        $this->props = array_merge($this->props, $props);

        return $this;
    }

    /**
     * Bind a single prop to the page's Vue components.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return $this
     */
    public function prop($name, $value)
    {
        $this->props[$name] = $value;

        return $this;
    }

    /**
     * Render page.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge([
            'components' => collect($this->components),
            'props'      => $this->props,
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

        throw new InvalidArgumentException("Property [{$name}] does not exist on ".static::class);
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
