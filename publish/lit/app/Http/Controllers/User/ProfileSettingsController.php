<?php

namespace Lit\Http\Controllers\User;

use Ignite\Crud\Controllers\CrudController;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Lit\Models\User;

class ProfileSettingsController extends CrudController
{
    /**
     * Authorize request for authenticated litstack-user and permission operation.
     * Operations: read, update.
     *
     * @param  Authorizable  $user
     * @param  string  $operation
     * @return bool
     */
    public function authorize(Authorizable $user, string $operation): bool
    {
        return $user->id == lit_user()->id;
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query($query)
    {
        $query->where('id', lit_user()->id);
    }
}
