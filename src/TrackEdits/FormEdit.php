<?php

namespace AwStudio\Fjord\TrackEdits;

use Illuminate\Database\Eloquent\Model;

class FormEdit extends Model
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

    public function getTimeAttribute()
    {
        return $this->created_at
            ->locale(fjord()->getLocale())
            ->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(\AwStudio\Fjord\Fjord\Models\FjordUser::class, 'fjord_user_id');
    }
}
