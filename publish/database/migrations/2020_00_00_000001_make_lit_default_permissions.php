<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MakeLitDefaultPermissions extends Migration
{
    protected $permissions = [
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

    /**
     * Create roles and permissions.
     *
     * @return void
     */
    public function up()
    {
        Role::firstOrCreate(['guard_name' => 'lit', 'name' => 'admin']);
        Role::firstOrCreate(['guard_name' => 'lit', 'name' => 'user']);

        $admin = Role::where('name', 'admin')->first();

        // create permissions and give them to admin
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['guard_name' => 'lit', 'name' => $permission]);
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
            Permission::where(['guard_name' => 'lit', 'name' => $permission])->delete();
        }

        Role::where(['guard_name' => 'lit', 'name' => 'admin'])->delete();
        Role::where(['guard_name' => 'lit', 'name' => 'user'])->delete();
    }
}
