<?php

namespace Fjord\Crud\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class FormBlockTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];
    protected $casts = [
        'value' => 'json',
    ];
}
