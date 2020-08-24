<?php

namespace Lit\Crud\Models;

use Lit\User\Models\LitUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelEdit extends Model
{
    /**
     * Wether this Model uses default timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * Appended accessors.
     *
     * @var array
     */
    protected $appends = ['time'];

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'json',
    ];

    /**
     * time attribute.
     *
     * @return string
     */
    public function getTimeAttribute()
    {
        return $this->created_at
            ->locale(lit()->getLocale())
            ->diffForHumans();
    }

    /**
     * User relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(LitUser::class, 'lit_user_id');
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
