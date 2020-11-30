<?php

namespace Ignite\Vue\Traits;

use Closure;
use Ignite\Contracts\Vue\Authorizable;
use Illuminate\Support\Collection;

trait RenderableAsProp
{
    /**
     * Return array of props that should be passed to Vue.
     *
     * @return array
     */
    abstract public function render(): array;

    /**
     * Rendering lifecycle hooks.
     *
     * @var array
     */
    protected $renderingHooks = [];

    /**
     * Get array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getRendered()->toArray();
    }

    /**
     * To json.
     *
     * @param  int    $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return $this->getRendered()->toJson($options);
    }

    /**
     * Add rendering lifecycle hook.
     *
     * @param  Closure $hook
     * @return $this
     */
    public function rendering(Closure $hook)
    {
        $this->renderingHooks[] = $hook;

        return $this;
    }

    /**
     * Get rendered.
     *
     * @return Collection
     */
    protected function getRendered(): Collection
    {
        foreach ($this->renderingHooks as $hook) {
            $hook($this);
        }

        $rendered = collect($this->render());

        $rendered = $this->authorized($rendered);

        // When all props are passed to the Vue application, they are converted
        // to an array using Laravel's collection method toArray. To ensure that
        // all nested objects are converted, the array is converted to a
        // collection instance, which then calls the toArray method on all it's items.
        return $rendered->map(function ($item) {
            if (is_array($item)) {
                return collect($item);
            }

            return $item;
        });
    }

    /**
     * Filter rendered collection authorized items.
     *
     * @param  Collection $rendered
     * @return Collection
     */
    protected function authorized(Collection $rendered): Collection
    {
        return $rendered->map(function ($item) {
            if ($item instanceof Collection) {
                return $this->authorized($item);
            }

            return $item;
        })->filter(function ($item) {
            if (! $item instanceof Authorizable) {
                return true;
            }

            return $item->check();
        });
    }

    /**
     * Parse to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
