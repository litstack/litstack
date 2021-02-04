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
    protected function upPermissions()
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
