<?php

namespace Ignite\Permissions\Controllers;

use Ignite\Permissions\Models\RolePermission;
use Ignite\Permissions\Requests\RolePermission\UpdateRolePermissionRequest;
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
