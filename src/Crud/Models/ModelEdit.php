<?php

namespace Fjord\Crud\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
        'payload' => 'json',
    ];

    public function getTimeAttribute()
    {
        return $this->created_at
            ->locale(fjord()->getLocale())
            ->diffForHumans();
    }

    /**
     * User relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\Fjord\User\Models\FjordUser::class, 'fjord_user_id');
    }

    /**
     * Model relation.
     *
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
