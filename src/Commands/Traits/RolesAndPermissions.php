<?php

namespace Fjord\Commands\Traits;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait RolesAndPermissions
{
    public function createDefaultRoles()
    {
        Role::firstOrCreate(['guard_name' => 'fjord', 'name' => 'admin']);
        Role::firstOrCreate(['guard_name' => 'fjord', 'name' => 'user']);
    }

    public function createDefaultPermissions()
    {
        $admin = Role::where('name', 'admin')->first();

        $permissions = [
            // Fjord users.
            'create fjord-users',
            'read fjord-users',
            'update fjord-users',
            'delete fjord-users',
            // Fjord user roles.
            'create fjord-user-roles',
            'read fjord-user-roles',
            'update fjord-user-roles',
            'delete fjord-user-roles',
            // Fjord user role permissions.
            'read fjord-role-permissions',
            'update fjord-role-permissions'
        ];

        // create permissions and give them to admin
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['guard_name' => 'fjord', 'name' => $permission]);
            $admin->givePermissionTo($permission);
        }
    }
}
