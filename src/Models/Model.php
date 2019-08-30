<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use AwStudio\Fjord\Form\Database\Traits\HasFormFields;
use AwStudio\Fjord\EloquentJs\CanEloquentJs;

class Model extends LaravelModel
{
    use CanEloquentJs, HasFormFields;

    public function translatedAttributes()
    {
        return $this->translatedAttributes;
    }
}
