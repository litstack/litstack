<?php

namespace Lit\Http\Controllers\Form\Pages;

use Ignite\Config\Form\Pages\HomeConfig;
use Ignite\Crud\Controllers\FormController;
use Lit\Models\User;

class HomeController extends FormController
{
    /**
     * Form config class.
     *
     * @var string
     */
    protected $config = HomeConfig::class;

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
        return $user->can("{$operation} pages");
    }
}
