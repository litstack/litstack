<?php

namespace LitApp\Controllers\Form\Dashboards;

use Lit\Crud\Controllers\FormController;
use Lit\User\Models\LitUser;

class WelcomeController extends FormController
{
    /**
     * Form config class.
     *
     * @var string
     */
    protected $config = App\Models\MainDashboard::class;

    /**
     * Authorize request for authenticated litstack-user and permission operation.
     * Operations: read, update.
     *
     * @param LitUser $user
     * @param string    $operation
     *
     * @return bool
     */
    public function authorize(LitUser $user, string $operation): bool
    {
        return true;
    }
}
