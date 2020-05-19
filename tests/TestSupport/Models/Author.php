<?php

namespace Fjord\Test\TestSupport\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;
}
