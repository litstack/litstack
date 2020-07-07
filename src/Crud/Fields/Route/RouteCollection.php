<?php

namespace Fjord\Crud\Fields\Route;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RouteCollection extends Collection
{
    protected $id;

    protected $title;

    protected $parent;

    /**
     * Create new RouteCollection isntance.
     *
     * @param string $name
     * @param array  $items
     */
    public function __construct(array $items, self $parent = null)
    {
        parent::__construct($items);
        $this->parent = $parent;
    }

    /**
     * Add named route to collection.
     *
     * @param  string         $title
     * @param  Closure|string $resolver
     * @return $this
     */
    public function route(string $title, $resolver)
    {
        $id = Str::snake($title);
        $this->items[$id] = new RouteItem($title, $id, $resolver, $this);

        return $this;
    }

    /**
     * Add route group.
     *
     * @param  string  $title
     * @param  Closure $closure
     * @return $this
     */
    public function group(string $title, Closure $closure)
    {
        $id = Str::snake($title);
        $this->items[$id] = $collection = new self([], $this);
        $collection->setId($id);
        $collection->setTitle($title);
        $closure($collection);

        return $this;
    }

    /**
     * Set route collection id.
     *
     * @param  string $id
     * @return void
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Get route collection id.
     *
     * @return string
     */
    public function getId()
    {
        if (! $this->parent) {
            return $this->id;
        }

        return $this->parent->getId().".{$this->id}";
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Find route.
     *
     * @param  string         $id
     * @return RouteItem|null
     */
    public function findRoute(string $id)
    {
        $dottedItems = Arr::dot($this->onlySelfToArray());

        return $dottedItems[$id]
            ?? null;
    }

    /**
     * Only self to array.
     *
     * @return array
     */
    protected function onlySelfToArray()
    {
        return $this->map(function ($item) {
            if ($item instanceof self) {
                return $item->onlySelfToArray();
            }

            return $item;
        })->all();
    }

    /**
     * Resolve route collection.
     *
     * @param  string $collection
     * @return self
     */
    public static function resolve($collection)
    {
        return app('fjord.crud.route.resolver')->resolve($collection);
    }
}
