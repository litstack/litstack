<?php

namespace Fjord\Vue\Page;

use Closure;
use Fjord\Support\HasAttributes;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Page
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
     * Root Vue component.
     *
     * @var string
     */
    protected $rootComponent = 'fj-page';

    /**
     * CrudIndex container slots.
     *
     * @var array
     */
    protected $slots = [];

    /**
     * Page title.
     *
     * @var string
     */
    protected $title;

    /**
     * Go back route & text.
     *
     * @var array|null
     */
    protected $back;

    /**
     * Navigation instance.
     *
     * @var Navigation
     */
    public $navigation;

    /**
     * Props that should be bound to child Vue components.
     *
     * @var array
     */
    protected $props = [];

    /**
     * View data.
     *
     * @var array
     */
    protected $viewData = [];

    /**
     * List of Blade Views.
     *
     * @var array
     */
    protected $views = [];

    /**
     * Create new Page instance.
     */
    public function __construct()
    {
        $this->navigation = new Navigation;
    }

    /**
     * Set go back route & text.
     *
     * @param  string $text
     * @param  string $route
     * @return $this
     */
    public function goBack(string $text, string $route)
    {
        $this->back = [
            'text'  => $text,
            'route' => $text,
        ];

        return $this;
    }

    /**
     * Set page title.
     *
     * @param  string $title
     * @return string
     */
    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get page title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

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
     * Add prop.
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
     * Render CrudIndex container for Vue.
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
            'slots'      => collect($this->slots),
            'title'      => $this->title,
            'navigation' => $this->navigation,
            'back'       => $this->back,
            'props'      => $this->props,
        ], $this->attributes);
    }

    /**
     * Format page to string.
     *
     * @return string
     */
    public function __toString()
    {
        return view('fjord::app')
            ->withComponent($this->rootComponent)
            ->withProps(array_merge([
                'page' => collect($this->render()),
            ], $this->props))
            ->withTitle($this->title)
            ->render();
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
