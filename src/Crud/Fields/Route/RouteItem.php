<?php

namespace Fjord\Crud\Fields\Route;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class RouteItem implements Arrayable, Jsonable
{
    protected $title;

    protected $id;

    protected $resolver;

    protected $resolved;

    public function __construct(string $title, string $id, Closure $resolver, $collection)
    {
        $this->title = $title;
        $this->id = $id;
        $this->resolver = $resolver;
        $this->collection = $collection;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->collection->getId().".{$this->id}";
    }

    public function toArray()
    {
        return $this->getId();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->resolve();
    }

    public function route()
    {
        return $this->resolve();
    }

    public function resolve()
    {
        if ($this->resolved) {
            return $this->resolved;
        }

        return $this->resolved = call_user_func($this->resolver, fjord()->getLocale());
    }
}
