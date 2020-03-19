<?php

namespace AwStudio\Fjord\RolesPermissions\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use AwStudio\Fjord\Fjord\Models\FjordUser;
use AwStudio\Fjord\RolesPermissions\Models\ModelRole;
use AwStudio\Fjord\RolesPermissions\Requests\UpdateUserRoleRequest;
use AwStudio\Fjord\RolesPermissions\Requests\IndexUserRoleRequest;

class FjordUserController extends Controller
{
    public function index(IndexUserRoleRequest $request)
    {
        return view('fjord::vue')->withComponent('fj-fjord-users')
            ->withTitle('Users')
            ->withProps([
                'roles' => Role::all(),
                'users' => FjordUser::all(),
                'user_roles' => ModelRole::where('model_type', 'AwStudio\Fjord\Fjord\Models\FjordUser')->get(),
            ]);
    }

    public function update(UpdateUserRoleRequest $request)
    {
        $user = FjordUser::findOrFail($request->user['id']);
        $role = Role::findOrFail($request->role['id']);

        if ($user->hasRole($role)) {
            $user->removeRole($role);
        } else {
            $user->syncRoles([$role]);
        }
    }
}
