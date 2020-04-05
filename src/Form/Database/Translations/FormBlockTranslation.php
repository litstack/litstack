<?php

namespace Fjord\Form\Database\Translations;

use Illuminate\Database\Eloquent\Model;

class FormBlockTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];
    protected $casts = [
        'value' => 'json',
    ];
}
