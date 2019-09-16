<?php

namespace AwStudio\Fjord\Fjord\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use AwStudio\Fjord\Form\Database\Traits\HasFormFields;
use AwStudio\Fjord\EloquentJs\CanEloquentJs;

class User extends Authenticatable
{
    use HasRoles, CanEloquentJs, HasFormFields;

}
