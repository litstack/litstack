<?php

use Ignite\Support\Migration\MigratePermissions;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class MakePermissions extends Migration
{
    use MigratePermissions;

    /**
     * Edit permission groups that should be created.
     *
     * Operations: read, update.
     *
     * @var array
     */
    protected $editPermissionGroups = [
        // Permissions to link permissions to roles in litstack.
        'lit-role-permissions',
    ];

    /**
     * Crud permission groups that should be created.
     *
     * Operations: create, read, update, delete.
     *
     * @var array
     */
    protected $crudPermissionGroups = [
        // Permissions to manage litstack users.
        'lit-users',

        // Permissions to manage permissions in litstack.
        'lit-user-roles',
    ];

    /**
     * Permission groups that should be deleted. In case you want to get rid of
     * previous created permissions.
     *
     * Operations: create, read, update, delete.
     *
     * @var array
     */
    protected $down = [
        //
    ];

    /**
     * Permissions to be created.
     *
     * @var array
     */
    protected $permissions = [];

    /**
     * Set permissions for groups.
     *
     * @return void
     */
    protected function buildPermissions()
    {
        $this->combineOperationsAndGroups(
            ['read', 'update'], $this->editPermissionGroups
        );
        $this->combineOperationsAndGroups(
            ['create', 'read', 'update', 'delete'], $this->crudPermissionGroups
        );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::firstOrCreate(['guard_name' => config('lit.guard'), 'name' => 'admin']);
        Role::firstOrCreate(['guard_name' => config('lit.guard'), 'name' => 'user']);
        $this->buildPermissions();
        $this->upPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->combineOperationsAndGroups(
            ['create', 'read', 'update', 'delete'], $this->down
        );
        $this->downPermissions();
    }
}
