<?php

namespace AwStudio\Fjord\Commands\Traits;

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
            'read user',
            'update user',
            'create user',
            'delete user',
            'read user-roles',
            'update user-roles',
            'read role-permissions',
            'update role-permissions'
        ];

        // create permissions and give them to admin
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['guard_name' => 'fjord', 'name' => $permission]);
            $admin->givePermissionTo($permission);
        }
    }

    public function createCrudPermissions()
    {
        $cruds = glob(fjord_resource_path("crud/*.php"));

        foreach ($cruds as $crud) {
            $name = str_replace('.php', '', basename($crud));

            $this->info("Making {$name} permissions");

            $permissions = [
                'create ' . $name,
                'read ' . $name,
                'update ' . $name,
                'delete ' . $name
            ];

            // create permissions and give them to admin
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['guard_name' => 'fjord', 'name' => $permission]);
            }
        }
    }
}
