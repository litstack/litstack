<?php

namespace Ignite\Vue\Traits;

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
     * Get rendered.
     *
     * @return Collection
     */
    protected function getRendered(): Collection
    {
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
                return $item;
            }

            return $item->check();
        });
    }
}
