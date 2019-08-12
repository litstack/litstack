<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $fillable = ['from_model_id', 'from_model_type', 'to_model_id', 'to_model_type'];

    public $timestamps = false;
}
