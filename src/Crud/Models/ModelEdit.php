<?php

namespace Fjord\Crud\Models;

use Illuminate\Database\Eloquent\Model;

class ModelEdit extends Model
{
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    protected $appends = ['time'];

    protected $casts = [
        'payload' => 'json'
    ];

    public function getTimeAttribute()
    {
        return $this->created_at
            ->locale(fjord()->getLocale())
            ->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(\Fjord\User\Models\FjordUser::class, 'fjord_user_id');
    }

    public function model(): morphTo
    {
        return $this->morphTo();
    }
}
