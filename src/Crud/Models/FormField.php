<?php

namespace Fjord\Crud\Models;

use Fjord\EloquentJs\CanEloquentJs;
use Spatie\MediaLibrary\Models\Media;
use Fjord\Crud\Models\Traits\HasFields;
use Fjord\Crud\Models\Traits\HasConfig;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Fjord\Crud\Models\Relations\EmptyRelation;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class FormField extends Model implements HasMedia, TranslatableContract
{
    use Translatable,
        CanEloquentJs,
        HasMediaTrait,
        HasConfig,
        HasFields;

    protected $translationModel = Translations\FormFieldTranslation::class;
    public $translatedAttributes = ['value'];

    public $fillable = ['collection', 'form_name', 'field_id', 'value'];
    protected $appends = ['translation', 'fields'];
    protected $with = ['translations'];

    protected $formable = 'value';

    /**
     * Get config key.
     *
     * @return string $key
     */
    public function getConfigKey()
    {
        return "form.{$this->collection}.{$this->form_name}";
    }

    public function isTranslatableFieldFillable()
    {
        return true;
    }

    public function isFieldFillable()
    {
        return true;
    }

    /**
     * Get many relations for relation field. Set eager loads from form field 
     * query builder.
     *
     * @return belongsToMany
     */
    public function form_field_relations()
    {
        return $this->formMany($this->form_field->model ?? '')
            ->setEagerLoads(
                $this->form_field->query->getEagerLoads()
            );
    }

    /**
     * Get single relation for relation field.
     *
     * @return void
     */
    public function form_field_relation()
    {
        return (new EmptyRelation($this->form_field->query, $this))
            ->where('id', $this->getTranslatedFormFieldValue($this->form_field));
    }

    /**
     * Get last edit.
     *
     * @return morphOne
     */
    public function getLastEditAttribute()
    {
        return $this->hasOne('Fjord\TrackEdits\FormEdit', 'form_name', 'form_name')
            ->where('collection', $this->collection)
            ->orderByDesc('id')
            ->with('user')
            ->first();
    }

    /**
     * Media relation.
     *
     * @return void
     */
    public function media_relation()
    {
        return $this->media()->where('media.collection_name', $this->form_field->id);
    }

    /**
     * Get trainslations attribute
     *
     * @return array
     */
    public function getTranslationAttribute()
    {
        return $this->getTranslationsArray();
    }

    /**
     * Set relation attribute for field.
     *
     * @return self
     */
    public function setFormRelation()
    {
        $this->setAttribute($this->field_id, $this->getFieldRelation());

        return $this;
    }

    /**
     * Set image relation attribute for field.
     *
     * @return self
     */
    public function setImageRelation()
    {
        $this->setAttribute($this->field_id, $this->getImageRelation());

        return $this;
    }

    /**
     * Get image relation.
     *
     * @param boolean $getQuery
     * @return void
     */
    public function getImageRelation($getQuery = false)
    {
        return $getQuery
            ? $this->media_relation()
            : $this->media_relation;
    }

    /**
     * Get field relation.
     *
     * @param boolean $getQuery
     * @return void
     */
    public function getFieldRelation($getQuery = false)
    {
        if ($this->field->many) {
            return $getQuery
                ? $this->form_field_relations()
                : $this->form_field_relations;
        }

        return $getQuery
            ? $this->form_field_relation()
            : $this->form_field_relation()->first();
    }

    /**
     * Get field value.
     *
     * @param Field $field
     * @return mixed
     */
    public function getFieldValue($field)
    {
        if ($this->translatable) {
            return $field->getFormFieldValue($this, app()->getLocale());
        }

        return $field->getFormFieldValue($this, config('translatable.fallback_locale'));
    }

    /**
     * Modify field values for vuejs.
     * For Example: transform boolean form_field to boolean value.
     
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $field = $this->getFieldAttribute();

        if (!$field) {
            return $array;
        }

        // Cast relation form fields.
        if ($field->isRelation()) {
            $array[$field->id] = $field->cast(
                $field->relation($this, $query = false)
            );

            return $array;
        }

        // Cast non relation fields.
        foreach ($array['translation'] as $locale => $values) {
            $array['translation'][$locale]['value'] = $field->cast(
                $field->getFormFieldValue($this, $locale)
            );
        }

        return $array;
    }


    /**
     * Get field instance for model.
     *
     * @return Field
     */
    public function getFieldAttribute()
    {
        return $this->getAttribute('fields')->first() ?? null;
    }

    /**
     * Filter fields from config.
     *
     * @return Field
     */
    public function getFieldsAttribute()
    {
        if (!array_key_exists('field_id', $this->attributes)) {
            return collect([]);
        }

        $fields = $this->config->form->getRegisteredFields();

        if (empty($fields)) {
            return collect([]);
        }
        $fields = $fields->filter(function ($field) {
            return $this->attributes['field_id'] == $field->id;
        });

        $fields = $this->getDynamicFieldValues($fields);

        $fields->first()->local_key = 'value';

        return $fields;
    }

    /**
     * Get attribute for FormField.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!array_key_exists('field_id', $this->attributes)) {
            return parent::getAttribute($key);
        }

        if ($this->attributes['field_id'] != $key) {
            return parent::getAttribute($key);
        }

        return $this->getFormattedFieldValue($this->field);
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

    /**
     * Modified to return relations for type "relation" or "block".
     * 
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params = [])
    {
        if ($method == ($this->field->id ?? '')) {
            return $this->getFormattedFormFieldValue($this->field, true);
        }

        return parent::__call($method, $params);
    }
}
