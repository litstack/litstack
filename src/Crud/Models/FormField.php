<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\Models\FormEdit;
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
    protected $fieldIds = [];

    /**
     * Fillable attributes.
     *
     * @var array
     */
    public $fillable = [
        'collection',
        'form_name',
        'field_id',
        'value',
        'field_type'
    ];

    /**
     * Eager loads.
     *
     * @var array
     */
    protected $with = [
        'translations',
        'media'
    ];

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json'
    ];

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
     * Update FormField.
     *
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function update(array $attributes = array(), array $options = array())
    {
        $translations = $this->getTranslationsArray();
        foreach (config('translatable.locales') as $locale) {
            if (!array_key_exists($locale, $attributes)) {
                continue;
            }
            $translation = array_merge($translations[$locale] ?? [], $attributes[$locale]);

            $attributes[$locale] = ['value' => $translation];
        }

        return parent::update($attributes, $options);
    }

    /**
     * Get translations array.
     *
     * @return array
     */
    public function getTranslationsArray(): array
    {
        $translations = [];

        foreach ($this->translations as $translation) {
            $translations[$translation->{$this->getLocaleKey()}] = $translation->value;
        }

        return $translations;
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

        return $this->getTranslatedFieldValue($field, $locale);
    }

    /**
     * Get translated field value.
     *
     * @param Field $field
     * @param string $locale
     * @return void
     */
    public function getTranslatedFieldValue($field, string $locale)
    {
        $value = $this->translation[$locale] ?? [];

        return $value[$field->id] ?? null;
    }

    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $attributes = parent::relationsToArray();

        foreach ($this->fields as $field) {
            if (!$field->isRelation()) {
                continue;
            }

            $attributes[$field->id] = $this->getFormattedFieldValue($field);
        }

        return $attributes;
    }

    /**
     * Get attribute for FormField.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        // Using fieldIds instead of fieldExists to avoid infinite loop 
        // when calling getAttribute('field').
        if (!in_array($key, $this->fieldIds)) {
            return parent::getAttribute($key);
        }

        return $this->getFormattedFieldValue(
            $this->findField($key)
        );
    }

    /**
     * Set field ids to be able to check if field exists in getAttribute method.
     *
     * @param array $ids
     * @return void
     */
    public function setFieldIds(array $ids)
    {
        $this->fieldIds = $ids;
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

        $model->setFieldIds($model->fields->map(function ($field) {
            return $field->id;
        })->toArray());

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
        if (!in_array($method, $this->fieldIds)) {
            return parent::__call($method, $params);
        }

        $field = $this->findField($method);

        if (!$field->isRelation()) {
            return parent::__call($method, $params);
        }

        return $field->relation($this, $query = true);
    }
}
