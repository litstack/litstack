<?php

namespace Fjord\Form\Database;

use Illuminate\Database\Eloquent\Model;

class FormRelation extends Model
{
    protected $fillable = ['from_model_id', 'from_model_type', 'to_model_id', 'to_model_type'];

    public $timestamps = false;
}
