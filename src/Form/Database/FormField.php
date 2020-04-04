<?php

namespace AwStudio\Fjord\Form\Database;

use Awobaz\Compoships\Compoships;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use AwStudio\Fjord\Support\Facades\Package;
use AwStudio\Fjord\EloquentJs\CanEloquentJs;
use AwStudio\Fjord\Support\NestedCollection;
use AwStudio\Fjord\Support\Facades\FormLoader;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Relations\Relation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

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
        return (new Relations\EmptyRelation($this->form_field->query, $this))
            ->where('id', $this->getTranslatedFormFieldValue($this->form_field));
    }

    /**
     * Get last edit.
     *
     * @return morphOne
     */
    public function getLastEditAttribute()
    {
        return $this->hasOne('AwStudio\Fjord\TrackEdits\FormEdit', 'form_name', 'form_name')
            ->where('collection', $this->collection)
            ->orderByDesc('id')
            ->with('user')
            ->first();
    }


    public function media_relation()
    {
        return $this->media()->where('media.collection_name', $this->form_field->id);
    }

    public function getFormFieldsPathAttribute()
    {
        return Package::get('aw-studio/fjord')
            ->getConfigFilePath("forms.{$this->collection}.{$this->form_name}");
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
            : $this->form_field_relation()->first();
    }

    public function getTranslatedFormFieldValue($form_field)
    {
        if ($form_field->translatable) {
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

        if (!$form_field) {
            return $array;
        }

        if (!in_array($form_field->type, ['boolean', 'block', 'relation', 'image', 'checkboxes'])) {
            return $array;
        }

        $value = $this->getFormattedFormFieldValue($this->form_field, false, false);

        if (in_array($form_field->type, ['checkboxes', 'boolean'])) {

            // Formated casts (json, array, boolean, etc...) must be put back to
            // their original place.
            $array['translation'][config('translatable.fallback_locale')]['value'] = $value;
        } else {

            // For relations add $form_field->id as array_key and set the
            // relation as value.
            $array[$form_field->id] = $value;
        }

        return $array;
    }

    /**
     * Modified to return relations for type "relation" or "block".
     */
    public function __call($method, $parameters)
    {
        if ($method == ($this->form_field->id ?? '')) {
            return $this->getFormattedFormFieldValue($this->form_field, true);
        }

        return parent::__call($method, $parameters);
    }

    public function getFormFieldAttribute()
    {
        return $this->form_fields->first() ?? null;
    }

    public function getFormFieldsAttribute()
    {
        if (!array_key_exists('field_id', $this->attributes)) {
            return new NestedCollection([]);
        }

        $form = FormLoader::load($this->form_fields_path, $this);

        if (!property_exists($form, 'form_fields')) {
            return new NestedCollection([]);
        }

        $form_fields = clone $form->form_fields;

        $form_fields = $form_fields->where('id', $this->attributes['field_id']);

        $form_fields = $this->getDynamicFieldValues($form_fields);

        $form_fields->first()->local_key = 'value';

        return $form_fields;
    }

    public function getAttribute($key)
    {
        if (!array_key_exists('field_id', $this->attributes)) {
            return parent::getAttribute($key);
        }

        if ($this->attributes['field_id'] != $key) {
            return parent::getAttribute($key);
        }

        return $this->getFormattedFormFieldValue($this->form_field);
    }

    public function registerMediaConversions(Media $media = null)
    {
        foreach (config('fjord.mediaconversions.default') as $key => $value) {
            $this->addMediaConversion($key)
                ->width($value[0])
                ->height($value[1])
                ->sharpen($value[2]);
        }
    }
}
