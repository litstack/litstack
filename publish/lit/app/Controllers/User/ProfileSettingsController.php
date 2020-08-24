<?php

namespace LitApp\Controllers\User;

use Lit\Crud\Controllers\CrudController;
use Lit\User\Models\LitUser;
use Illuminate\Database\Eloquent\Builder;

class ProfileSettingsController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = LitUser::class;

    /**
     * Authorize request for authenticated lit-user and permission operation.
     * Operations: read, update.
     *
     * @param LitUser $user
     * @param string    $operation
     *
     * @return bool
     */
    public function authorize(LitUser $user, string $operation): bool
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
