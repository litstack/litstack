<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\Models\FormEdit;
use Fjord\EloquentJs\CanEloquentJs;
use Fjord\Crud\Fields\Blocks\Blocks;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class FormField extends Model implements HasMedia, TranslatableContract
{
    use Translatable,
        HasMediaTrait,
        Concerns\HasConfig,
        Concerns\HasFields,
        Concerns\HasMedia;

    /**
     * Translation model class.
     *
     * @var string
     */
    protected $translationModel = Translations\FormFieldTranslation::class;

    /**
     * Translated attributes
     *
     * @var array
     */
    public $translatedAttributes = ['value'];

    /**
     * Field id.
     *
     * @var string
     */
    protected $fieldId;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    public $fillable = ['collection', 'form_name', 'field_id', 'value', 'field_type'];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['translation', 'fields'];

    /**
     * Eager loads.
     *
     * @var array
     */
    protected $with = ['translations', 'media'];

    /**
     * Get config key.
     *
     * @return string $key
     */
    public function getConfigKey()
    {
        return "form.{$this->collection}.{$this->form_name}";
    }

    /**
     * Register media conversions for field.
     *
     * @param Media $media
     * @return void
     */
    public function registerMediaConversions(Media $media = null)
    {
        return $this->registerCrudMediaConversions($media);
    }

    /**
     * Get last edit attribute.
     *
     * @return morphOne
     */
    public function getLastEditAttribute()
    {
        // Must be an attribute because relations dont have access to 
        // $this->collection.
        return $this->hasOne(FormEdit::class, 'form_name', 'form_name')
            ->where('collection', $this->collection)
            ->orderByDesc('id')
            ->with('user')
            ->first();
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
     * Get value for model FormField.
     *
     * @param FormField $formField
     * @param string $locale
     * @return mixed
     */
    public function getFormFieldValue(FormField $formField, string $locale)
    {
        $value = $formField->translation[$locale] ?? [];

        return $value['value'] ?? null;
    }

    /**
     * Get field value.
     *
     * @param Field $field
     * @return mixed
     */
    public function getFieldValue($field)
    {
        if ($field->isRelation()) {
            return $field->relation($this, $query = false);
        }

        if ($this->translatable) {
            $locale = app()->getLocale();
        } else {
            $locale = config('translatable.fallback_locale');
        }

        return $this->getTranslatedFieldValue($locale);
    }

    /**
     * Get translated field value.
     *
     * @param string $locale
     * @return void
     */
    public function getTranslatedFieldValue(string $locale)
    {
        $value = $this->translation[$locale] ?? [];

        return $value['value'] ?? null;
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($attributes['translation'] as $locale => $values) {
            $attributes['translation'][$locale]['value'] = $this->getFormattedFieldValue($this->field);
        }

        return $attributes;
    }

    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $attributes = parent::relationsToArray();

        if (!$this->field->isRelation()) {
            return $attributes;
        }

        $attributes[$this->field->id] = $this->getFormattedFieldValue($this->field);

        return $attributes;
    }

    /**
     * Get field instance for model.
     *
     * @return Field
     */
    public function getFieldAttribute()
    {
        if (!array_key_exists('field_id', $this->attributes)) {
            return null;
        }

        $field = $this->config->form->findField($this->field_id);

        $field->local_key = 'value';

        return $field;
    }

    /**
     * Get fields.
     *
     * @return Collection
     */
    public function getFieldsAttribute()
    {
        return collect([$this->field]);
    }

    /**
     * Get attribute for FormField.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if ($key != $this->fieldId) {
            return parent::getAttribute($key);
        }

        if (!$this->fieldId) {
            if ($this->field->id == $key) {
                return parent::getAttribute($key);
            }
        }

        return $this->getFormattedFieldValue($this->field);
    }

    /**
     * Set field id to be able to check if field exists in getAttribute method.
     *
     * @param string $id
     * @return void
     */
    public function setFieldId(string $id)
    {
        $this->fieldId = $id;
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @param  string|null  $connection
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = parent::newFromBuilder($attributes, $connection);

        $model->setFieldId($model->field->id);

        return $model;
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
        if ($method != ($this->field->id ?? '')) {
            return parent::__call($method, $params);
        }

        if (!$this->field->isRelation()) {
            return parent::__call($method, $params);
        }

        return $this->field->relation($this, $query = true);
    }
}
