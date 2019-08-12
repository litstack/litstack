<?php

namespace AwStudio\Fjord\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class ModelContentTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['content'];
    protected $casts = [
        'data' => 'json',
    ];
}
