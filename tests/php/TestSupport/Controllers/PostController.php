<?php

namespace FjordTest\TestSupport\Controllers;

use Fjord\Crud\Controllers\CrudController;
use Fjord\User\Models\FjordUser;
use Illuminate\Database\Eloquent\Builder;

class PostController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = \FjordTest\TestSupport\Models\Post::class;

    /**
     * Authorize request for permission operation and authenticated fjord-user.
     * Operations: create, read, update, delete.
     *
     * @param FjordUser $user
     * @param string    $operation
     *
     * @return bool
     */
    public function authorize(FjordUser $user, string $operation): bool
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
