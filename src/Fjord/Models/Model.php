<?php

namespace Fjord\Fjord\Models;

use Spatie\MediaLibrary\Models\Media;
use Fjord\Crud\Models\Model as CrudModel;
use Fjord\Form\Database\Traits\HasFormFields;
use Fjord\EloquentJs\CanEloquentJs;
use Fjord\TrackEdits\TrackEdits;

class Model extends CrudModel
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
