<?php

namespace AwStudio\Fjord\User\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use AwStudio\Fjord\Fjord\Models\FjordUser;
//use AwStudio\Fjord\User\Models\ModelRole;
use AwStudio\Fjord\User\Requests\UpdateUserRoleRequest;
use AwStudio\Fjord\User\Requests\IndexUserRoleRequest;

class FjordUserController extends Controller
{
    public function showIndex(IndexUserRoleRequest $request)
    {
        return view('fjord::app')->withComponent('fj-fjord-users')
            ->withTitle('Users')
            ->withProps([
                'usersCount' => FjordUser::count(),
                //'users' => FjordUser::all(),
                //'user_roles' => ModelRole::where('model_type', 'AwStudio\Fjord\Fjord\Models\FjordUser')->get(),
            ]);
    }

    public function fetchIndex(Request $request)
    {
        return FjordUser::index($request);
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
