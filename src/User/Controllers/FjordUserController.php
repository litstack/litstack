<?php

namespace AwStudio\Fjord\User\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use AwStudio\Fjord\Fjord\Models\FjordUser;
//use AwStudio\Fjord\User\Models\ModelRole;
use AwStudio\Fjord\User\Requests\UpdateUserRoleRequest;
use AwStudio\Fjord\User\Requests\IndexUserRoleRequest;
use AwStudio\Fjord\Support\IndexTable;
use AwStudio\Fjord\Support\Facades\Package;

class FjordUserController extends Controller
{
    public function showIndex(IndexUserRoleRequest $request)
    {
        $config = Package::config('aw-studio/fjord', 'users.table');

        return view('fjord::app')->withComponent('fj-users')
            ->withTitle('Users')
            ->withProps([
                'usersCount' => FjordUser::count(),
                'config' => $config
            ]);
    }

    public function deleteAll(Request $request)
    {
        return IndexTable::deleteSelected(FjordUser::class, $request);
    }

    public function fetchIndex(Request $request)
    {
        return IndexTable::get(FjordUser::query(), $request);
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
