<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Content extends Model implements HasMedia, TranslatableContract
{
    use HasMediaTrait, Translatable;

    //protected $visible = ['id', 'form_fields', 'data'];
    protected $translationModel = Translations\ContentTranslation::class;
    protected $appends = ['fields'];
    protected $guarded = ['image', 'media'];
    public $fillable = ['data', 'model', 'model_id', 'type', 'link'];
    public $translatedAttributes = ['data'];

    /**
     * Append the image column to each query.
     *
     */
    public function getTranslationAttribute()
    {
        return $this->getTranslationsArray();
    }

    public function getImageAttribute()
    {
        return $this->getMedia('image');
    }

    public function relation()
    {
        return $this->belongsTo($this->model, 'model_id');
    }

    public function getIsTranslateableAttribute()
    {
        // check, if the mode is translatable
        $reflect = new \ReflectionClass($this->relation);
        if ($reflect->implementsInterface('Astrotomic\Translatable\Contracts\Translatable')){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Add locales to Attributes.
     */
    public function toArray()
    {
        $ids = [];
        foreach($this->translation ?? [] as $locale => $value) {
            $data = $this->translation[$locale];
            $this->setAttribute(
                $locale,
                array_merge($value, array_key_exists('data', $data) ? $data['data'] ?? [] : [])
            );
            $ids = array_merge(['data'], array_keys($this->data));
        }
        foreach(config('translatable.locales') as $locale) {
            if(! array_key_exists($locale, $this->translation)) {
                $this->setAttribute(
                    $locale,
                    collect($ids)->map(function($key) {
                        return [$key => null];
                    })->collapse()->toArray()
                );
            }
        }
        $this->syncOriginal();

        return parent::toArray();
    }

    public function getFieldsAttribute()
    {
        // TODO: only fields used by model
        if(! $this->type) {
            return [];
        }

        $fields = config('fjord-repeatables.' . $this->type);
        foreach($fields as $key => $field) {
            if($field['type'] == 'image') {
                $fields[$key]['model'] = get_class($this);
            }
        }

        return $fields;
    }
}
