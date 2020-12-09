<?php

namespace Lit\Http\Controllers\User;

use Ignite\Crud\Controllers\CrudController;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Lit\Models\User;

class UserController extends CrudController
{
    /**
     * Authorize request for permission operation and authenticated litstack-user.
     * Operations: create, read, update, delete.
     *
     * @param  Authorizable  $user
     * @param  string  $operation
     * @param  string  $id
     * @return bool
     */
    public function authorize(Authorizable $user, string $operation, $id = null): bool
    {
        if ($operation == 'update') {
            return $user->id == $id;
        }

        if ($operation == 'delete' && $user->id == $id) {
            return false;
        }

        return $user->can("{$operation} lit-users");
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query($query)
    {
        //
    }
}
