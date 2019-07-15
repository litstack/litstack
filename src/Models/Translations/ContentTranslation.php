<?php

namespace AwStudio\Fjord\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['data'];
    protected $casts = [
        'data' => 'json',
    ];
}
