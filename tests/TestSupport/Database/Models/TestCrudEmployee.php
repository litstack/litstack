<?php

namespace Fjord\Test\TestSupport;

use Illuminate\Database\Eloquent\Model;

class TestCrudEmployee extends Model
{
    protected $table = 'crud_employees';

    protected $guarded = [];

    public $timestamps = false;
}
