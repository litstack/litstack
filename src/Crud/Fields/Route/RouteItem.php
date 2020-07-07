<?php

namespace Fjord\Crud\Fields\Route;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class RouteItem implements Arrayable, Jsonable
{
    /**
     * Route item title.
     *
     * @var string
     */
    protected $title;

    /**
     * Route item id.
     *
     * @var string
     */
    protected $id;

    /**
     * Route item resolver.
     *
     * @var string
     */
    protected $resolver;

    /**
     * Route item resolver.
     *
     * @var Closure
     */
    protected $resolved;

    /**
     * Create new RouteItem instance.
     *
     * @param  string          $title
     * @param  string          $id
     * @param  Closure         $resolver
     * @param  RouteCollection $collection
     * @return void
     */
    public function __construct($title, $id, Closure $resolver, RouteCollection $collection = null)
    {
        $this->title = $title;
        $this->id = $id;
        $this->resolver = $resolver;
        $this->collection = $collection;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        if (! $this->collection) {
            return $this->id;
        }

        return $this->collection->getId().".{$this->id}";
    }

    /**
     * To array.
     *
     * @return string
     */
    public function toArray()
    {
        return $this->getId();
    }

    /**
     * To json.
     *
     * @param  int    $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->resolve();
    }

    /**
     * Get route.
     *
     * @return string
     */
    public function route()
    {
        return $this->resolve();
    }

    /**
     * Resolve route.
     *
     * @return string
     */
    public function resolve()
    {
        if ($this->resolved) {
            return $this->resolved;
        }

        return $this->resolved = call_user_func($this->resolver, app()->getLocale());
    }
}
