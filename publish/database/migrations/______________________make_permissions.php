<?php

use Ignite\Support\Migration\MigratePermissions;
use Illuminate\Database\Migrations\Migration;

class MakePermissions extends Migration
{
    use MigratePermissions;

    /**
     * Edit permissions groups that should be created.
     * Operations: read, update.
     *
     * @var array
     */
    protected $editPermissionGroups = [
        // Permissions to link permissions and roles in litstack.
        'lit-role-permissions',
    ];

    /**
     * Crud permission groups that should be created.
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
     * Permissions that should be deleted.
     * Operations: create, read, update, delete.
     * @var array
     */
    protected $down = [

    ];

    /**
     * Set permissions for groups.
     *
     * @return void
     */
    protected function makePermissions()
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
        $this->makePermissions();
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
        $this->makePermissions();
        $this->downPermissions();
    }
}
