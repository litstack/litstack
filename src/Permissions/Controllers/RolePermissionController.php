<?php

namespace Ignite\Permissions\Controllers;

use Ignite\Permissions\Models\RolePermission;
use Ignite\Permissions\Requests\RolePermission\UpdateRolePermissionRequest;
use Illuminate\Contracts\Cache\Repository as Cache;
use Spatie\Permission\Models\Role;

class RolePermissionController
{
    /**
     * Cache repository instance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Create new RolePermissionController instance.
     *
     * @param  Cache $cache
     * @return void
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Toggle role permission.
     *
     * @param  UpdateRolePermissionRequest $request
     * @return void
     */
    public function update(UpdateRolePermissionRequest $request)
    {
        $role = Role::findOrFail($request->role['id']);

        if ($role->hasPermissionTo($request->permission)) {
            $role->revokePermissionTo($request->permission);
        } else {
            $role->givePermissionTo($request->permission);
        }

        $this->cache->forget('spatie.permission.cache');

        return RolePermission::all();
    }
}
