<?php

namespace Fjord\User\Controllers;

use Fjord\User\Models\FjordUser;
use Fjord\Support\Facades\Config;
use Fjord\User\Requests\FjordUserReadRequest;
use Fjord\User\Requests\FjordUserDeleteRequest;

class ProfileController
{
    /**
     * Show profile update.
     *
     * @return void
     */
    public function show()
    {
        $config = Config::get('user.profile_settings')->get('form');

        return view('fjord::app')
            ->withComponent('fj-profile-settings')
            ->withProps([
                'model' => crud(fjord_user()),
                'config' => $config
            ]);
    }
}
