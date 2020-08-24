<?php

namespace LitApp\Controllers\Form\Pages;

use Lit\Crud\Controllers\FormController;
use Lit\User\Models\LitUser;
use LitApp\Config\Form\Pages\HomeConfig;

class HomeController extends FormController
{
    /**
     * Form config class.
     *
     * @var string
     */
    protected $config = HomeConfig::class;

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
        return $user->can("{$operation} pages");
    }
}
