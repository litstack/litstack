<?php

namespace Ignite\Page;

use Ignite\Contracts\Page\Page;
use Ignite\Support\HasAttributes;
use Ignite\Vue\Components\BladeComponent;
use Ignite\Vue\Traits\HasVueComponents;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use InvalidArgumentException;

abstract class BasePage implements Page
{
    use HasAttributes,
        HasVueComponents,
        Concerns\ManagesWrapper;

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
     * Extend the page.
     *
     * @param  string $alias
     * @param         $name
     * @return void
     */
    public function extend($alias, $name = null)
    {
        $extensions = new Collection(
            app('lit.page')->getExtensions($alias, $name)
        );

        foreach ($extensions as $extension) {
            $extension($this);
        }
    }

    /**
     * Add Vue component to stack.
     *
     * @param  \Ignite\Vue\Component|string $component
     * @return \Ignite\Vue\Component|mixed
     */
    public function component($component)
    {
        $component = component($component);

        if ($this->inWrapper()) {
            $this->wrapper->component($component);
        } else {
            $this->components[] = $component;
        }

        return $component;
    }

    /**
     * Bind view to page.
     *
     * @param  string|View    $view
     * @param  array          $data
     * @return BladeComponent
     */
    public function view($view, array $data = [])
    {
        if (! $view instanceof View) {
            $view = view($view, $data);
        }

        $this->views[] = $view;

        return $this->component('lit-blade')->prop('view', $view);
    }

    /**
     * Get views.
     *
     * @return array
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Bind livewire component to page.
     *
     * @param  string         $component
     * @param  array          $data
     * @return BladeComponent
     */
    public function livewire($component, $data = [])
    {
        return $this->view('litstack::partials.livewire', [
            'component' => $component,
            'data'      => $data,
        ]);
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
        foreach ($this->views as $view) {
            $view->with($this->viewData);
        }

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
