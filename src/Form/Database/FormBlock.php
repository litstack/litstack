<?php

namespace AwStudio\Fjord\Form\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use AwStudio\Fjord\EloquentJs\CanEloquentJs;
use AwStudio\Fjord\Support\Facades\FormLoader;

class FormBlock extends Model implements HasMedia, TranslatableContract
{
    use HasMediaTrait, Translatable, CanEloquentJs, HasFormFields;

    protected $translationModel = Translations\FormBlockTranslation::class;

    public $timestamps = false;
    public $translatedAttributes = ['value'];

    protected $fillable = ['field_id', 'model_type', 'model_id', 'type', 'content', 'order_column'];
    protected $appends = ['image', 'form_fields', 'translation'];
    protected $casts = ['value' => 'json'];

    public function getFormFieldsPathAttribute()
    {
        return fjord_resource_path("repeatables/" . $this->type . ".php");
    }

    public function isFormFieldFillable($field)
    {
        return true;
    }

    public function isTranslatableFormFieldFillable()
    {
        return true;
    }

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
        foreach(config('translatable.locales') as $locale) {
            if(! array_key_exists($locale, $attributes)) {
                continue;
            }
            $attributes[$locale] = ['value' => $attributes[$locale]];
        }
        return parent::update($attributes, $options);
    }

    public function model(): morphTo
    {
        return $this->morphTo();
    }

    public function getFormFieldsAttribute()
    {
        $fields = clone FormLoader::loadFields($this->form_fields_path, $this);

        return $this->getDynamicFieldValues($fields);
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
