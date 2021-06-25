<?php

namespace Ignite\Support\Migration;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait MigratePermissions
{
    /**
     * Create permissions and give the to admin.
     *
     * @return void
     */
    protected function upPermissions($roles_with_permissions = null)
    {
        $admins = Role::where('guard_name', config('lit.guard'))
            ->where('name', 'admin')
            ->get();

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['guard_name' => config('lit.guard'), 'name' => $permission]);
            foreach ($admins as $admin) {
                $admin->givePermissionTo($permission);
            }
        }

        if ($roles_with_permissions) {
            foreach ($roles_with_permissions as $role => $permissions) {
                $role = Role::where('name', $role)->first();
                if ($role) {
                    foreach ($permissions as $permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
        }
    }

    /**
     * Delete permissions and revoke them from admin.
     *
     * @return void
     */
    protected function downPermissions()
    {
        $admins = Role::where('guard_name', config('lit.guard'))
            ->where('name', 'admin')
            ->get();

        $permissions = Permission::where('guard_name', config('lit.guard'))
            ->whereIn('name', $this->permissions)
            ->get();

        foreach ($permissions as $permission) {
            foreach ($admins as $admin) {
                $admin->revokePermissionTo($permission->name);
            }
            $permission->delete();
        }
    }

    /**
     * Combine operations and groups.
     *
     * @param  array $operations
     * @param  array $groups
     * @return void
     */
    public function combineOperationsAndGroups(array $operations, array $groups)
    {
        foreach ($operations as $operation) {
            foreach ($groups as $group) {
                $permission = "{$operation} {$group}";

                if (in_array($permission, $this->permissions)) {
                    continue;
                }

                $this->permissions[] = $permission;
            }
        }
    }
}
