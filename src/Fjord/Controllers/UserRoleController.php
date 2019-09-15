<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use AwStudio\Fjord\Fjord\Models\ModelRole;
use AwStudio\Fjord\Fjord\Requests\UpdateUserRoleRequest;
use AwStudio\Fjord\Fjord\Requests\IndexUserRoleRequest;

class UserRoleController extends Controller
{
    public function index(IndexUserRoleRequest $request)
    {
        return view('fjord::vue')->withComponent('user-roles')
            ->withTitle('Users')
            ->withProps([
                'roles' => Role::all(),
                'users' => User::all(),
                'user_roles' => ModelRole::where('model_type', 'App\Models\User')->get(),
            ]);
    }

    public function update(UpdateUserRoleRequest $request)
    {
        $user = User::findOrFail($request->user['id']);
        $role = Role::findOrFail($request->role['id']);

        if($user->hasRole($role)){
            $user->removeRole($role);
        }else{
            $user->syncRoles([$role]);
        }
    }
}
