<?php

namespace AwStudio\Fjord\Fjord\Models;

use Spatie\MediaLibrary\Models\Media;
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

    public function getMediaConversionsAttribute()
    {
        return config('fjord.mediaconversions.repeatables');
    }

    public function registerMediaConversions(Media $media = null)
    {
        foreach ($this->mediaConversions as $key => $value) {
            $this->addMediaConversion($key)
                  ->width($value[0])
                  ->height($value[1])
                  ->sharpen($value[2]);
        }
    }
}
