<?php

namespace Fjord\Crud\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class FormFieldTranslation extends Model
{
    /**
     * Timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = ['value'];

    /*
    public function model()
    {
        return $this->belongsTo('Fjord\Models\PageContent', 'form_field_id');
    }
    */
}
