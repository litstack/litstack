<?php

namespace Fjord\User\Models;

use Fjord\Auth\Models\FjordSession;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Fjord\Auth\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class FjordUser extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable,
        HasRoles,
        CanResetPassword;

    /**
     * Guard name.
     *
     * @var string
     */
    protected $guard_name = 'fjord';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password', 'locale',
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

    /**
     * Send password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $link = route('fjord.password.reset', $token);

        $link .= '?email=' . urlencode($this->email);

        $this->notify(new ResetPasswordNotification($link));
    }

    /**
     * Fjord sessions.
     *
     * @return hasMany
     */
    public function sessions()
    {
        return $this->hasMany(FjordSession::class)->orderByDesc('last_activity');
    }

    /**
     * Has role admin scope.
     *
     * @param $query
     * @return $query
     */
    public function scopeAdmin($query)
    {
        return $query->role('admin');
    }

    /**
     * Has role user scope.
     *
     * @param  $query
     * @return $query
     */
    public function scopeUser($query)
    {
        return $query->role('user');
    }

    /**
     * Ordered Roles by permission count.
     *
     * @return MorphToMany
     */
    public function ordered_roles()
    {
        return $this->roles()->withCount('permissions')->orderByDesc('permissions_count');
    }
}
