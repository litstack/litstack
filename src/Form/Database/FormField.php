<?php

namespace AwStudio\Fjord\Form\Database;

use Illuminate\Database\Eloquent\Model;
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
        HasFormfields;

    protected $translationModel = Translations\FormFieldTranslation::class;
    public $translatedAttributes = ['value'];

    public $fillable = ['collection', 'form_name', 'field_id', 'value'];
    protected $appends = ['translation', 'form_fields'];

    protected $formable = 'value';

    /*
    public $form_field_id_column = 'field_id';
    public $form_field_value_column = 'value';
    protected $formable = 'value';
    */

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

    /**
     * Get formatted values for the given form_field type.
     *
     * @return void
     */
    public function getFormattedFormFieldValue($form_field, $builder = false)
    {
        switch($form_field->type ?? null) {
            case 'relation':
                return $this->getFormFieldRelation();
            case 'boolean':
                return (bool) $this->value;
            case 'select':
                return $form_field->options[$this->value];
            case 'block':
                return $this->getBlocks($form_field, $builder);
            case 'image':
                return $this->form_field->maxFiles > 1
                    ? $this->getImageRelation(true)->get()
                    : $this->getImageRelation(true)->first();
            default:
                return $this->value;
        }
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
     * Modified to return relations for type "relation" or "block"
     */
    public function __call($key, $parameters)
    {
        if (($this->attributes['field_id'] ?? null) == $key) {

            if($this->form_field->type == 'relation') {
                return $this->getFormFieldRelation(true);
            }

            if($this->form_field->type == 'block') {
                return $this->getBlocks($this->form_field, true);
            }

            if($this->form_field->type == 'image') {
                return $this->getImageRelation(true);
            }

        }

        return parent::__call($key, $parameters);
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

    /*
    public function getTranslatedFormContent()
    {
        if ($this->form_field->translatable) {
            $attributes = $this->getTranslationsArray()[app()->getLocale()] ?? $this->attributes;
            return $attributes[$this->form_field_value_column];
        } else {
            return $this->getTranslationsArray()[config('translatable.fallback_locale')][$this->form_field_value_column];
        }
    }

    public function getAttribute($key)
    {
        if (($this->attributes['field_name'] ?? null) == $key) {
            return $this->getFormContent();
        }

        if ($key == 'content') {
            return $this->getTranslatedFormContent();
        }

        return parent::getAttribute($key);
    }
    */


    /*
    public function getFieldAttribute()
    {
        return $this->fields[0] ?? null;
    }

    public function getFieldsAttribute()
    {
        if (! array_key_exists('field_name', $this->attributes)) {
            return form_collect([]);
        }

        $page = fjord()->getPage($this->page_name ?? null) ?? [];

        if (! array_key_exists('fields', $page)) {
            return form_collect([]);
        }

        $fields = $page['fields'];

        $query = $fields->where('id', $this->attributes['field_name']);

        if(! $query->first()) {
            return form_collect([]);
        }

        $field = clone $query->first();

        if (! $field) {
            return form_collect([]);
        }

        $fields = $this->getDynamicFieldValues(form_collect([$field]));

        if ($field->type != 'relation') {
            $field->id = 'content';
        }

        return form_collect([$field]);
    }
    */
}
