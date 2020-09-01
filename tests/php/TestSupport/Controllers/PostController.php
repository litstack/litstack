<?php

namespace Tests\TestSupport\Controllers;

use Ignite\Crud\Controllers\CrudController;
use Illuminate\Database\Eloquent\Builder;
use Lit\Models\User;

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
     * @param User   $user
     * @param string $operation
     *
     * @return bool
     */
    public function authorize(User $user, string $operation): bool
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
