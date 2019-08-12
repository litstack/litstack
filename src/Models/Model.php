<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use Traits\HasFormFields, Traits\CanEloquentJs, Traits\HasRepeatables;

    public function translatedAttributes()
    {
        return $this->translatedAttributes;
    }
}
