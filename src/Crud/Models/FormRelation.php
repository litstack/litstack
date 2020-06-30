<?php

namespace Fjord\Crud\Models;

use Illuminate\Database\Eloquent\Model;

class FormRelation extends Model
{
    /**
     * Fillables.
     *
     * @var array
     */
    protected $fillable = [
        'from_model_id',
        'from_model_type',
        'to_model_id',
        'to_model_type',
        'field_id',
        'order_column',
    ];

    /**
     * Timestamps.
     *
     * @var bool
     */
    public $timestamps = false;
}
