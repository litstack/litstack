<?php

namespace Ignite\Crud\Fields\Route;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RouteCollection extends Collection
{
    /**
     * Route collection id.
     *
     * @var string
     */
    protected $id;

    /**
     * Route collection title.
     *
     * @var string
     */
    protected $title;

    /**
     * Route collection parent.
     *
     * @var null|RouteCollection
     */
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
     * @param  string         $id
     * @param  Closure|string $resolver
     * @return $this
     */
    public function route($title, $id, $resolver)
    {
        $this->items[$id] = new RouteItem($title, $id, $resolver, $this);

        return $this;
    }

    /**
     * Add route group.
     *
     * @param  string  $title
     * @param  string  $id
     * @param  Closure $closure
     * @return $this
     */
    public function group($title, $id, Closure $closure)
    {
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
    public function setId($id)
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

    /**
     * Set title.
     *
     * @param  string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title.
     *
     * @return void
     */
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
    public function findRoute($id)
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
        return lit_app('crud.route.resolver')->resolve($collection);
    }
}
