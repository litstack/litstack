<?php

namespace Tests\TestSupport\Controllers;

use Lit\Crud\Controllers\CrudController;
use Lit\User\Models\LitUser;
use Illuminate\Database\Eloquent\Builder;

class BlockInBlockController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = \Tests\TestSupport\Models\Post::class;

    public function authorize(LitUser $user, string $operation): bool
    {
        return true;
    }

    public function query(): Builder
    {
        return $this->model::query();
    }
}
