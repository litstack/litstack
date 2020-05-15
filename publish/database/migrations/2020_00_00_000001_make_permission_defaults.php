<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class MakePermissionDefaults extends Migration
{
    protected $permissions = [
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

    /**
     * Create roles and permissions.
     *
     * @return void
     */
    public function up()
    {
        Role::firstOrCreate(['guard_name' => 'fjord', 'name' => 'admin']);
        Role::firstOrCreate(['guard_name' => 'fjord', 'name' => 'user']);

        $admin = Role::where('name', 'admin')->first();

        // create permissions and give them to admin
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['guard_name' => 'fjord', 'name' => $permission]);
            $admin->givePermissionTo($permission);
        }
    }

    /**
     * Delete roles and permissions.
     *
     * @return void
     */
    public function down()
    {
        $admin = Role::where('name', 'admin')->first();

        foreach ($this->permissions as $permission) {
            $admin->revokePermissionTo($permission);
            Permission::where(['guard_name' => 'fjord', 'name' => $permission])->delete();
        }

        Role::where(['guard_name' => 'fjord', 'name' => 'admin'])->delete();
        Role::where(['guard_name' => 'fjord', 'name' => 'user'])->delete();
    }
}
