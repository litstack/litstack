<?php

namespace AwStudio\Fjord\Form\Database;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use AwStudio\Fjord\EloquentJs\CanEloquentJs;
use AwStudio\Fjord\Form\FormFieldCollection;
use AwStudio\Fjord\Support\Facades\FormLoader;

class FormField extends Model implements HasMedia, TranslatableContract
{
    use Translatable,
        CanEloquentJs,
        HasMediaTrait,
        Traits\HasFormFields,
        Traits\FormatFormFields;

    protected $translationModel = Translations\FormFieldTranslation::class;
    public $translatedAttributes = ['value'];

    public $fillable = ['collection', 'form_name', 'field_id', 'value'];
    protected $appends = ['translation', 'form_fields'];
    protected $with = ['translations'];

    protected $formable = 'value';

   public function isTranslatableFormFieldFillable()
   {
       return true;
   }

    public function isFormFieldFillable()
    {
        return true;
    }

    public function form_field_relations()
    {
        return $this->formMany($this->form_field->model ?? '');
    }

    public function form_field_relation()
    {
        return $this->hasOne($this->form_field->model, 'id', 'value');
    }

    public function media_relation()
    {
        return $this->media()->where('media.collection_name', $this->form_field->id);
    }

    public function getFormFieldsPathAttribute()
    {
        return fjord_resource_path("{$this->collection}/{$this->form_name}.php");
    }

    public function getTranslationAttribute()
    {
        return $this->getTranslationsArray();
    }

    public function setFormRelation()
    {
        $this->setAttribute($this->field_id, $this->getFormFieldRelation());

        return $this;
    }

    public function setImageRelation()
    {
        $this->setAttribute($this->field_id, $this->getImageRelation());

        return $this;
    }

    public function getImageRelation($getQuery = false)
    {
        return $getQuery
            ? $this->media_relation()
            : $this->media_relation;
    }

    public function getFormFieldRelation($getQuery = false)
    {
        if ($this->form_field->many) {
            return $getQuery
                ? $this->form_field_relations()
                : $this->form_field_relations;
        }

        return $getQuery
            ? $this->form_field_relation()
            : $this->form_field_relation;
    }

    public function getTranslatedFormFieldValue($form_field)
    {
        if($form_field->translatable) {
            $value = $this->translation[app()->getLocale()] ?? [];
        } else {
            $value = $this->translation[config('translatable.fallback_locale')] ?? [];
        }

        return $value['value'] ?? null;
    }

    /**
     * Modify values for vuejs.
     * For Example: transform boolean form_field to boolean value.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $form_field = $this->getFormFieldAttribute();

        if(! $form_field) {
            return $array;
        }

        if(! in_array($form_field->type, ['boolean', 'block', 'relation', 'image'])) {
            return $array;
        }

        $value = $this->getFormattedFormFieldValue($this->form_field);

        if($form_field->type == 'boolean') {
            $array['translation'][config('translatable.fallback_locale')]['value'] = $value;
        } else {
            $array[$form_field->id] = $value;
        }

        return $array;
    }

    /**
     * Modified to return relations for type "relation" or "block".
     */
     public function __call($method, $parameters)
     {
         if($form_field = $this->findFormField($method)) {
             return $this->getFormattedFormFieldValue($form_field, true);
         }

         return parent::__call($method, $parameters);
     }

    public function getFormFieldAttribute()
    {
        return $this->form_fields->first() ?? null;
    }

    public function getFormFieldsAttribute()
    {
        if(! array_key_exists('field_id', $this->attributes)) {
            return new FormFieldCollection([]);
        }

        $form = FormLoader::load($this->form_fields_path, $this);

        if(! property_exists($form, 'fields')) {
            return new FormFieldCollection([]);
        }

        $fields = clone $form->fields;

        $fields = $fields->where('id', $this->attributes['field_id']);

        $fields = $this->getDynamicFieldValues($fields);

        $fields->first()->local_key = 'value';

        return $fields;
    }

    public function getAttribute($key)
    {
        if(! array_key_exists('field_id', $this->attributes)) {
            return parent::getAttribute($key);
        }

        if($this->attributes['field_id'] != $key) {
            return parent::getAttribute($key);
        }

        return $this->getFormattedFormFieldValue($this->form_field);
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
