<?php

namespace Ignite\Crud\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class RepeatableTranslation extends Model
{
    public $table = 'lit_repeatable_translations';

    /**
     * No timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = ['value'];

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];
}
