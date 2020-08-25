<?php

namespace Ignite\Commands\Traits;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait RolesAndPermissions
{
    /**
     * Create default roles.
     *
     * @return void
     */
    public function createDefaultRoles()
    {
        Role::firstOrCreate(['guard_name' => 'lit', 'name' => 'admin']);
        Role::firstOrCreate(['guard_name' => 'lit', 'name' => 'user']);
    }

    /**
     * Create default permissions.
     *
     * @return void
     */
    public function createDefaultPermissions()
    {
        $admin = Role::where('name', 'admin')->first();

        $permissions = [
            // Lit users.
            'create lit-users',
            'read lit-users',
            'update lit-users',
            'delete lit-users',
            // Lit user roles.
            'create lit-user-roles',
            'read lit-user-roles',
            'update lit-user-roles',
            'delete lit-user-roles',
            // Lit user role permissions.
            'read lit-role-permissions',
            'update lit-role-permissions',
        ];

        // create permissions and give them to admin
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['guard_name' => 'lit', 'name' => $permission]);
            $admin->givePermissionTo($permission);
        }
    }
}
