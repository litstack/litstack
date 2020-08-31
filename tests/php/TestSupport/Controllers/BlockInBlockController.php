<?php

namespace Tests\TestSupport\Controllers;

use Ignite\Crud\Controllers\CrudController;
use Lit\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BlockInBlockController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = \Tests\TestSupport\Models\Post::class;

    public function authorize(User $user, string $operation): bool
    {
        return true;
    }

    public function query(): Builder
    {
        return $this->model::query();
    }
}
