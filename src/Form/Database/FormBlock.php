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
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormBlock extends Model implements HasMedia, TranslatableContract
{
    use HasMediaTrait,
        Translatable,
        CanEloquentJs,
        Traits\HasFormFields,
        Traits\FormatFormFields;

    protected $translationModel = Translations\FormBlockTranslation::class;

    public $timestamps = false;
    public $translatedAttributes = ['value'];
    protected $formFieldIds = [];

    protected $fillable = ['field_id', 'model_type', 'model_id', 'type', 'content', 'order_column'];
    protected $appends = ['form_fields', 'translation'];
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

    public function getTranslationAttribute()
    {
        return collect($this->getTranslationsArray())->map(function($item) {
            return array_pop($item);
        })->toArray();
    }

    public function setFormFieldIds($ids)
    {
        $this->formFieldIds = $ids;
    }

    public function getFormFieldIds()
    {
        return $this->formFieldIds;
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

    public function toArray()
    {
        $array = parent::toArray();

        foreach($this->form_fields as $form_field) {

            if(! in_array($form_field->type, ['boolean', 'relation', 'image'])) {
                continue;
            }

            $value = $this->getFormattedFormFieldValue($form_field);

            if($form_field->type == 'boolean') {
                $array['translation'][config('translatable.fallback_locale')][$form_field->id] = $value;
            } else {
                $array[$form_field->id] = $value;
            }
            //$array[$form_field->id] = $this->getMedia($form_field->id)->toArray();
        }

        return $array;
    }

    public function getTranslatedFormFieldValue($form_field)
    {
        if($form_field->translatable) {
            $values = $this->translation[app()->getLocale()] ?? [];
        } else {
            $values = $this->translation[config('translatable.fallback_locale')] ?? [];
        }

        return $values[$form_field->id] ?? null;
    }

    public function getAttribute($key)
    {
        if(in_array($key, $this->formFieldIds) && ! array_key_exists($key, $this->attributes)) {

            return $this->getFormattedFormFieldValue(
                $this->findFormField($key)
            );

        }

        return parent::getAttribute($key);
    }


    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = parent::newFromBuilder($attributes, $connection);

        $model->setFormFieldIds(
            $model->form_fields->pluck('id')->toArray()
        );

        return $model;
    }
}
