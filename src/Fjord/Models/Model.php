<?php

namespace Fjord\Fjord\Models;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Fjord\Form\Database\Traits\HasFormFields;
use Fjord\EloquentJs\CanEloquentJs;
use Fjord\TrackEdits\TrackEdits;

class Model extends LaravelModel
{
    use CanEloquentJs, HasFormFields, TrackEdits;

    public function translatedAttributes()
    {
        if (!is_translatable($this)) {
            return [];
        }

        return $this->translatedAttributes;
    }
}
