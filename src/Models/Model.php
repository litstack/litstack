<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function translatedAttributes()
    {
        return $this->translatedAttributes;
    }
}
