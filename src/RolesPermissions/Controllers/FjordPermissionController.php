<?php

namespace AwStudio\Fjord\RolesPermissions\Controllers;

use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use AwStudio\Fjord\RolesPermissions\Models\RolePermission;
use AwStudio\Fjord\RolesPermissions\Requests\UpdateRolePermissionRequest;
use AwStudio\Fjord\RolesPermissions\Requests\IndexRolePermissionRequest;

class FjordPermissionController extends Controller
{
    public function index(IndexRolePermissionRequest $request)
    {
        return view('fjord::app')->withComponent('fj-fjord-permissions')
            ->withTitle('Permissions')
            ->withProps([
                'roles' => Role::all(),
                'permissions' => Permission::all(),
                'role_permissions' => RolePermission::all()
            ]);
    }

    public function update(UpdateRolePermissionRequest $request)
    {
        $role = Role::findOrFail($request->role['id']);
        $permission = $request->permission['name'];

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        \Cache::forget('spatie.permission.cache');
    }
}
