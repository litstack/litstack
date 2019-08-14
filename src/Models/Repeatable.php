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
    use HasMediaTrait, Translatable, Traits\CanEloquentJs;

    protected $translationModel = Translations\RepeatableTranslation::class;

    public $timestamps = false;
    public $translatedAttributes = ['content'];

    protected $fillable = ['page_name', 'model_type', 'model_id', 'block_name', 'type', 'content'];
    protected $appends = ['image', 'fields', 'translation'];
    protected $casts = [
        'content' => 'json',
    ];

    /**
     * Append the image column to each query.
     *
     */
    public function getTranslationAttribute()
    {
        return collect($this->getTranslationsArray())->map(function($item) {
            return array_pop($item);
        })->toArray();
    }

    public function update(array $attributes = array(), array $options = array()) {
        //dd($attributes, $options);
        foreach(config('translatable.locales') as $locale) {
            if(! array_key_exists($locale, $attributes)) {
                continue;
            }
            $attributes[$locale] = ['content' => $attributes[$locale]];
        }
        return parent::update($attributes, $options);
    }

    public function model(): morphTo
    {
        return $this->morphTo();
    }

    public function getFieldsAttribute()
    {
        $path = str_replace('.', '/', $this->type);
        return require fjord_resource_path("repeatables/{$path}.php");
    }

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
