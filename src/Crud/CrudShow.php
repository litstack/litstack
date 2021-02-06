<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Contracts\Crud\CrudCreate;
use Ignite\Contracts\Crud\CrudUpdate;
use Illuminate\Support\Str;

class CrudShow extends BaseCrudShow implements CrudCreate, CrudUpdate
{
    /**
     * Add only to create page.
     *
     * @param  Closure $closure
     * @return void
     */
    public function onlyOnCreate(Closure $closure)
    {
        if ($this->isCreate()) {
            $closure($this);
        }
    }

    /**
     * Add only to update page.
     *
     * @param  Closure $closure
     * @return void
     */
    public function onlyOnUpdate(Closure $closure)
    {
        if (! $this->isCreate()) {
            $closure($this);
        }
    }

    /**
     * Determines if page is update.
     *
     * @return bool
     */
    public function isCreate()
    {
        $route = request()->route();

        return str_contains($route->getName(), '.create')
            || Str::endsWith($route->getName(), '.store');
    }
}
