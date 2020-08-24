<?php

namespace Lit\Permissions\Controllers;

use Lit\Permissions\Models\RolePermission;
use Lit\Permissions\Requests\RolePermission\UpdateRolePermissionRequest;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class RolePermissionController
{
    public function update(UpdateRolePermissionRequest $request)
    {
        $role = Role::findOrFail($request->role['id']);

        if ($role->hasPermissionTo($request->permission)) {
            $role->revokePermissionTo($request->permission);
        } else {
            $role->givePermissionTo($request->permission);
        }

        Cache::forget('spatie.permission.cache');

        return RolePermission::all();
    }
}
