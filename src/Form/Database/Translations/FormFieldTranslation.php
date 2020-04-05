<?php

namespace Fjord\Form\Database\Translations;

use Illuminate\Database\Eloquent\Model;

class FormFieldTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];

    public function model()
    {
        return $this->belongsTo('Fjord\Models\PageContent', 'form_field_id');
    }
}
