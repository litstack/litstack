<?php

namespace Ignite\Crud\Config\Traits;

use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Support\Facades\Config;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Builder;

trait HasCrudShow
{
    /**
     * Controller instance.
     *
     * @var CrudBaseController
     */
    protected $controllerInstance;

    /**
     * Get a new controller instance.
     *
     * @return void
     */
    public function controllerInstance()
    {
        if (! is_null($this->controllerInstance)) {
            return $this->controllerInstance;
        }

        return $this->controllerInstance = app()->make($this->controller, [
            'config' => Config::get(get_class($this)),
        ]);
    }

    /**
     * Get the initial query.
     *
     * @return Builder
     */
    public function query()
    {
        return $this->controllerInstance()->getQuery();
    }

    /**
     * Authorize the page for the given user and operation.
     *
     * @param  Authorizable $user
     * @param  string       $operation
     * @return bool
     */
    public function authorize(Authorizable $user, $operation)
    {
        return $this->controllerInstance()->authorize($user, $operation);
    }
}
