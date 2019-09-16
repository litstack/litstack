<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use App\Models\User;
use AwStudio\Fjord\Fjord\Models\ModelRole;

class UserController extends Controller
{
    public function roleIndex()
    {
        return view('fjord::vue')->withComponent('user-roles')
            ->withTitle('Users')
            ->withProps([
                'roles' => Role::all(),
                'users' => User::all(),
                'user_roles' => ModelRole::where('model_type', 'App\Models\User')->get(),
            ]);
    }

}
