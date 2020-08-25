<?php

namespace Lit\Controllers\Form\Dashboards;

use Ignite\Crud\Controllers\FormController;
use Ignite\User\Models\User;

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
     * @param  User   $user
     * @param  string $operation
     * @return bool
     */
    public function authorize(User $user, string $operation): bool
    {
        return true;
    }
}
