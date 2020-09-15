<?php

namespace Ignite\Crud\Controllers\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ManagesQuery
{
    /**
     * Initial query.
     *
     * @var Builder
     */
    protected $query;

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query($query);

    /**
     * Resolve the initial query.
     *
     * @return Builder
     */
    public function getQuery()
    {
        if ($this->query) {
            return clone $this->query;
        }

        $this->query($this->query = $this->initialQuery());

        return clone $this->query;
    }

    /**
     * Get the initial query.
     *
     * @return Builder
     */
    public function initialQuery()
    {
        if (! $this->config->has('parent')) {
            return $this->model::query();
        }

        $parentController = $this->config->parentConfig()->controllerInstance();

        $parent = $parentController->getQuery()->find(
            $this->getParentId()
        );

        if (is_null($parent)) {
            $model = $parentController->getModel();
            $parent = new $model;
        }

        return $parent->{$this->config->relation}();
    }

    /**
     * Gets the parent id.
     *
     * @return int|void
     */
    protected function getParentId()
    {
        $search = str_replace(
            '.', '_', $this->config->parentConfig()->getKey()
        );

        try {
            $route = Route::getRoutes()->match(request());
        } catch (HttpException $e) {
            return;
        }

        if (! $route) {
            return;
        }

        foreach ($route->parameters as $parameter => $value) {
            if ($search == $parameter) {
                return (int) $value;
            }
        }
    }
}
