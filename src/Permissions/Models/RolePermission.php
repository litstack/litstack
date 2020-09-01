<?php

namespace Ignite\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /**
     * Database table name.
     *
     * @var string
     */
    protected $table = 'role_has_permissions';
}
