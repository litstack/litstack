<?php

namespace AwStudio\Fjord\Fjord\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use AwStudio\Fjord\EloquentJs\CanEloquentJs;
use Illuminate\Auth\Passwords\CanResetPassword;
use AwStudio\Fjord\Auth\Notifications\ResetPasswordNotification;
use AwStudio\Fjord\Form\Database\Traits\HasFormFields;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class FjordUser extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable,
        HasRoles,
        CanResetPassword,
        CanEloquentJs,
        HasFormFields;


    protected $guard_name = 'fjord';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'locale'
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

    public function sendPasswordResetNotification($token)
    {
        $link = route('fjord.password.reset', $token);

        $link .= '?email=' . urlencode($this->email);

        $this->notify(new ResetPasswordNotification($link));
    }

    public function scopeAdmin($query)
    {
        return $query->role('admin');
    }

    public function scopeUser($query)
    {
        return $query->role('user');
    }
}
