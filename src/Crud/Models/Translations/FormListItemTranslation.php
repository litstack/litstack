<?php

namespace Fjord\Crud\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class FormListItemTranslation extends Model
{
    /**
     * No timestamps.
     *
     * @var boolean
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
        'value' => 'json'
    ];
}
