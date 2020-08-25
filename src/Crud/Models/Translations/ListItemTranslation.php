<?php

namespace Ignite\Crud\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class ListItemTranslation extends Model
{
    public $table = 'lit_list_item_translations';

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
