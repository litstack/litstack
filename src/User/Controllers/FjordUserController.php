<?php

namespace Fjord\User\Controllers;

use Illuminate\Http\Request;
use Fjord\Support\IndexTable;
use Fjord\User\Models\FjordUser;
use Fjord\Config\Traits\HasIndex;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Fjord\User\Requests\IndexFjordUserRequest;
use Fjord\User\Requests\UpdateUserRoleRequest;

class FjordUserController extends Controller
{
    /**
     * Show user index.
     *
     * @param IndexFjordUserRequest $request
     * @return void
     */
    public function showIndex(IndexFjordUserRequest $request)
    {
        $config = fjord()->config('user.user_index')->get(HasIndex::class);

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
