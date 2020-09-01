<?php

namespace Ignite\Crud\Fields\Route;

use Closure;
use InvalidArgumentException;

class RouteCollectionResolver
{
    /*
     * The array of collection resolvers.
     *
     * @var array
     */
    protected $resolvers = [];

    /**
     * The resolved engine instances.
     *
     * @var array
     */
    protected $resolved = [];

    /**
     * Register a new collection resolver.
     *
     * @param  string   $id
     * @param  \Closure $resolver
     * @return void
     */
    public function register($id, Closure $resolver)
    {
        unset($this->resolved[$id]);

        $this->resolvers[$id] = $resolver;
    }

    /**
     * Resolve an collection instance by id.
     *
     * @param  string          $id
     * @return RouteCollection
     *
     * @throws \InvalidArgumentException
     */
    public function resolve($id)
    {
        if (isset($this->resolved[$id])) {
            return $this->resolved[$id];
        }

        if (isset($this->resolvers[$id])) {
            $collection = $this->resolved[$id] = new RouteCollection([]);
            $collection->setId($id);

            call_user_func(
                $this->resolvers[$id],
                $collection
            );

            return $collection;
        }

        throw new InvalidArgumentException("Route collection [{$id}] not found.");
    }
}
