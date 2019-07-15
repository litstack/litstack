<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Repeatable extends Model implements HasMedia, TranslatableContract
{
    use HasMediaTrait, Translatable;

    protected $translationModel = Translations\RepeatableTranslation::class;

    public $timestamps = false;
    public $translatedAttributes = ['content'];

    protected $fillable = ['page_name', 'block_name', 'type', 'content'];
    protected $appends = ['image'];
    protected $casts = [
        'content' => 'json',
    ];


    public function registerMediaConversions(Media $media = null)
    {
        foreach (config('fjord.mediaconversions.repeatables') as $key => $value) {
            $this->addMediaConversion($key)
                  ->width($value[0])
                  ->height($value[1])
                  ->sharpen($value[2]);
        }
    }


    /**
     * Append the image column to each query.
     *
     */
    public function getImageAttribute()
    {
        return $this->getMedia('image');
    }

}
