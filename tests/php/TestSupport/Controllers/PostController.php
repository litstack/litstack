<?php

namespace Tests\TestSupport\Controllers;

use Lit\Crud\Controllers\CrudController;
use Lit\User\Models\LitUser;
use Illuminate\Database\Eloquent\Builder;

class PostController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = \Tests\TestSupport\Models\Post::class;

    /**
     * Authorize request for permission operation and authenticated lit-user.
     * Operations: create, read, update, delete.
     *
     * @param LitUser $user
     * @param string    $operation
     *
     * @return bool
     */
    public function authorize(LitUser $user, string $operation): bool
    {
        return true;
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
    {
        return $this->model::query();
    }
}
