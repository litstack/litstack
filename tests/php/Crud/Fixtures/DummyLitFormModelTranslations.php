<?php

namespace Tests\Crud\Fixtures;

use Illuminate\Database\Eloquent\Model;

class DummyLitFormModelTranslations extends Model
{
    public $table = 'dummy_lit_form_model_translations';
    public $timestamps = false;
    protected $fillable = ['value'];
    protected $casts = [
        'value' => 'json',
    ];
}
