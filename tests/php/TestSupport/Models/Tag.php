<?php

namespace FjordTest\TestSupport\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];
}
