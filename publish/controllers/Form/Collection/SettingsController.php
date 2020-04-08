<?php

namespace App\Http\Controllers\Fjord\Form\Collection;

use Fjord\Fjord\Models\FjordUser;
use Fjord\Form\Controllers\FormController;

class SettingsController extends FormController
{
    /**
     * Authorize request for permission operation and authenticated fjord-user.
     * Operations: read, update
     *
     * @param FjordUser $user
     * @param string $operation
     * @return boolean
     */
    public function authorize(FjordUser $user, string $operation): bool
    {
        return $user->can("{$operation} settings");
    }
}
