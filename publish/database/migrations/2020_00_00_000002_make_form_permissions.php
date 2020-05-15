<?php

use Fjord\Support\Migration\MigratePermissions;
use Illuminate\Database\Migrations\Migration;

class MakeFormPermissions extends Migration
{
    use MigratePermissions;

    /**
     * Permissions groups that should be created for all forms.
     *
     * @var array
     */
    protected $groups = [
        'pages',
        'settings'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->getPermissions();
        $this->upPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->getPermissions();
        $this->downPermissions();
    }

    /**
     * Set permissions for groups.
     *
     * @return void
     */
    protected function getPermissions()
    {
        foreach ($this->groups as $group) {
            $this->permissions[] = "read {$group}";
            $this->permissions[] = "update {$group}";
        }
    }
}
