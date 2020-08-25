<?php

namespace Lit\Controllers\User;

use Ignite\Crud\Controllers\CrudController;
use Ignite\User\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ProfileSettingsController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Authorize request for authenticated litstack-user and permission operation.
     * Operations: read, update.
     *
     * @param  User   $user
     * @param  string $operation
     * @return bool
     */
    public function authorize(User $user, string $operation): bool
    {
        return $user->id == lit_user()->id;
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
    {
        return $this->model::where('id', lit_user()->id);
    }
}
