<?php

namespace FjordApp\Controllers\Form\Dashboards;

use Fjord\Crud\Controllers\FormController;
use Fjord\User\Models\FjordUser;

class MainDashboardController extends FormController
{
    /**
     * Form config class.
     *
     * @var string
     */
    protected $config = App\Models\MainDashboard::class;

    /**
     * Authorize request for authenticated fjord-user and permission operation.
     * Operations: read, update.
     *
     * @param FjordUser $user
     * @param string    $operation
     *
     * @return bool
     */
    public function authorize(FjordUser $user, string $operation): bool
    {
        return true;
    }
}
