<?php

namespace Ignite\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class ModelRole extends Model
{
    public function getTable()
    {
        return config('permission.table_names.model_has_roles', parent::getTable());
    }
}
