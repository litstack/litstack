<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use AwStudio\Fjord\Fjord\Models\RolePermission;
use AwStudio\Fjord\Fjord\Requests\RolePermissionUpdateRequest;

class RolePermissionController extends Controller
{
    public function index()
    {
        // TODO: Role/Permission Table
        return view('fjord::vue')->withComponent('role-permissions')
            ->withTitle('Users')
            ->withProps([
                'roles' => Role::all(),
                'permissions' => Permission::all(),
                'role_permissions' => RolePermission::all()
            ]);
    }

    public function update(RolePermissionUpdateRequest $request)
    {
        $role = Role::findOrFail($request->role['id']);
        $permission = $request->permission['name'];

        if($role->hasPermissionTo($permission)){
            $role->revokePermissionTo($permission);
        }else{
            $role->givePermissionTo($permission);
        }
    }
}
