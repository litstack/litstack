<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Contracts\Crud\CrudCreate;
use Ignite\Contracts\Crud\CrudUpdate;

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
}
