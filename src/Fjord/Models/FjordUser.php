<?php

namespace AwStudio\Fjord\Fjord\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use AwStudio\Fjord\Form\Database\Traits\HasFormFields;
use AwStudio\Fjord\EloquentJs\CanEloquentJs;
use Illuminate\Notifications\Notifiable;

class FjordUser extends Authenticatable
{
    use Notifiable, HasRoles, CanEloquentJs, HasFormFields;


    protected $guard_name = 'fjord';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
