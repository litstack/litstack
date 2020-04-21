<?php

namespace Fjord\Crud\Models;

use Illuminate\Database\Eloquent\Model;

class FormEdit extends Model
{
    /**
     * No timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * Appends
     *
     * @var array
     */
    protected $appends = ['time'];

    /**
     * Human readable time.
     *
     * @return string
     */
    public function getTimeAttribute()
    {
        return $this->created_at
            ->locale(fjord()->getLocale())
            ->diffForHumans();
    }

    /**
     * Fjord user.
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(\Fjord\User\Models\FjordUser::class, 'fjord_user_id');
    }
}
