<?php

namespace Ignite\Crud\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class FormTranslation extends Model
{
    public $table = 'lit_form_translations';

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
