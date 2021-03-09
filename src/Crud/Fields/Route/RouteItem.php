<?php

namespace Ignite\Crud\Fields\Route;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

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
     * Check's if the route is active and returns value if value is not null.
     *
     * @param  mixed $value
     * @return mixed
     */
    public function isActive($value = null)
    {
        $pattern = trim($this->decodeRoute($trimmed = true));

        if ($this->decodeRoute($trimmed = true) != '' && ! in_array($this->decodeRoute($trimmed = true), config('translatable.locales'))) {
            $pattern .= '*';
        }

        if ($pattern == '') {
            $patterns = [$pattern, '/'];
        } else {
            $patterns = [$pattern];
        }
        if (! Request::is(...$patterns)) {
            return false;
        }

        return $value !== null ? $value : true;
    }

    /**
     * Get trimmed route. This method is used to prepare the resolved route for
     * the [Request::is] check.
     *
     * @return string
     */
    public function trimmed()
    {
        $trimmed = Str::replaceFirst(config('app.url'), '', $this->resolve());

        return trim($trimmed, '/');
    }

    /**
     * Get the current decoded route info for the route.
     *
     * @param  bool   $trimmed
     * @return string
     */
    public function decodeRoute($trimmed = false)
    {
        return rawurldecode($trimmed ? $this->trimmed() : $this->route());
    }

    /**
     * Get route.
     *
     * @return string
     */
    public function route()
    {
        $route = $this->resolve();

        return $route == '' ? '/' : $route;
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
}
