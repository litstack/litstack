<?php

namespace Ignite\Crud\Casts;

use Ignite\Crud\Fields\Route\RouteCollection;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class Route implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string                              $key
     * @param  mixed                               $value
     * @param  array                               $attributes
     * @return RouteItem|null
     */
    public function get($model, $key, $value, $attributes)
    {
        return $this->findRoute($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string                              $key
     * @param  array                               $value
     * @param  array                               $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return (string) $value;
    }

    /**
     * Find route by id.
     *
     * @param  string         $id
     * @return RouteItem|void
     */
    protected function findRoute($id)
    {
        $partials = explode('.', $id);
        $collection = array_shift($partials);
        $id = implode('.', $partials);

        try {
            return RouteCollection::resolve($collection)->findRoute($id);
        } catch (InvalidArgumentException $e) {
            return;
        }
    }
}
