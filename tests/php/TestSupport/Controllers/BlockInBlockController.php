<?php

namespace FjordTest\TestSupport\Controllers;

use Fjord\Crud\Controllers\CrudController;
use Fjord\User\Models\FjordUser;
use Illuminate\Database\Eloquent\Builder;

class BlockInBlockController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = \FjordTest\TestSupport\Models\Post::class;

    public function authorize(FjordUser $user, string $operation): bool
    {
        return true;
    }

    public function query(): Builder
    {
        return $this->model::query();
    }
}
