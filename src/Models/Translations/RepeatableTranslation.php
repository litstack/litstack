<?php

namespace AwStudio\Fjord\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class RepeatableTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['content'];
    protected $casts = [
        'content' => 'json',
    ];

}
