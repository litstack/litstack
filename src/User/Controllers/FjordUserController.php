<?php

namespace Fjord\User\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Fjord\Fjord\Models\FjordUser;
use Fjord\User\Requests\UpdateUserRoleRequest;
use Fjord\User\Requests\IndexFjordUserRequest;
use Fjord\Support\IndexTable;
use Fjord\Support\Facades\Package;

class FjordUserController extends Controller
{
    public function showIndex(IndexFjordUserRequest $request)
    {
        $config = Package::config('aw-studio/fjord', 'users.table');

        return view('fjord::app')->withComponent('fj-users')
            ->withTitle('Users')
            ->withProps([
                'usersCount' => FjordUser::count(),
                'config' => $config,
            ]);
    }

    public function deleteAll(Request $request)
    {
        IndexTable::deleteSelected(FjordUser::class, $request);
        return response(['message' => __f('fj.deleted_all', ['count' => count($request->ids)])]);
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
