<?php

namespace Fjord\Crud\Casts;

use Fjord\Crud\Fields\Route\RouteCollection;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Route implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string                              $key
     * @param  mixed                               $value
     * @param  array                               $attributes
     * @return array
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
     * @param  string $id
     * @return void
     */
    protected function findRoute(string $id)
    {
        $partials = explode('.', $id);
        $collection = array_shift($partials);
        $id = implode('.', $partials);

        return RouteCollection::resolve($collection)->findRoute($id);
    }
}
