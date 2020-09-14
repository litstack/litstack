<?php

namespace Tests\TestSupport\Controllers;

use Ignite\Crud\Controllers\CrudController;
use Lit\Models\User;

class PostController extends CrudController
{
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
}
