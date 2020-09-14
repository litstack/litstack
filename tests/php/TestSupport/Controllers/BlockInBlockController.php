<?php

namespace Tests\TestSupport\Controllers;

use Ignite\Crud\Controllers\CrudController;
use Lit\Models\User;

class BlockInBlockController extends CrudController
{
    public function authorize(User $user, string $operation): bool
    {
        return true;
    }
}
