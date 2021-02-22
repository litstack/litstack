<?php

namespace Ignite\Crud\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Ignite\Crud\RelationField;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

/**
 * The LitFormModel stores field data in as json in a column.
 */
abstract class LitFormModel extends Model implements HasMedia, TranslatableContract
{
    use Translatable,
        Concerns\HasConfig,
        Concerns\HasFields,
        Traits\HasMedia;

    /**
     * "value" is translatable but since non translatable fields are stored in
     * the value field it is important to not set value as a translatedAttribute
     * here.
     *
     * @var array
     */
    public $translatedAttributes = [];

    /**
     * Field ids.
     *
     * @var array
     */
    protected $fieldIds = [];

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = ['value' => 'json'];

    /**
     * Translations array.
     *
     * @var array|null
     */
    protected $translationsArray;

    /**
     * Get translation attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTranslationAttribute()
    {
        if (! is_null($this->translationsArray)) {
            return $this->translationsArray;
        }

        return $this->translationsArray = $this->getTranslationsArray();
    }

    /**
     * Get translations array.
     *
     * @return array
     */
    public function getTranslationsArray(): array
    {
        $translations = [];

        foreach (config('translatable.locales') as $locale) {
            $translation = $this->translations->where('locale', $locale)->first();

            if (! $translation) {
                continue;
            }

            $value = array_merge(
                $this->getTranslatedAttributesFromTranslation($translation),
                $translation->value ?? []
            );

            foreach ($this->fields as $field) {
                if (! $field->translatable) {
                    continue;
                }

                if (! array_key_exists($field->local_key, $value)) {
                    $value[$field->local_key] = $this->getFormattedFieldValue($field, $locale);
                }

                $translations[$translation->{$this->getLocaleKey()}] = $value;
            }
        }

        return $translations;
    }

    /**
     * Get translated attribute and the corresponding values from translation model.
     *
     * @param  Model $translation
     * @return array
     */
    public function getTranslatedAttributesFromTranslation(Model $translation)
    {
        $attributes = [];
        foreach ($translation->toArray() as $name => $value) {
            if (! in_array($name, $this->translatedAttributes)) {
                continue;
            }

            $attributes[$name] = $value;
        }

        return $attributes;
    }

    /**
     * Prepare attributes for save.
     *
     * @param  array $attributes
     * @return array
     */
    public function prepareAttributesForSave($attributes)
    {
        $translations = $this->getTranslationsArray();

        foreach (config('translatable.locales') as $locale) {
            if (! array_key_exists($locale, $attributes)) {
                continue;
            }

            $translation = array_merge($translations[$locale] ?? [], $attributes[$locale]);

            $translatedAttributes = ['value' => []];

            foreach ($translation as $name => $value) {
                if (! in_array($name, $this->translatedAttributes)) {
                    $translatedAttributes['value'][$name] = $value;
                } else {
                    $translatedAttributes[$name] = $value;
                }
            }

            $attributes[$locale] = $translatedAttributes;
        }

        $attributes['value'] = $this->value ?? [];
        foreach ($attributes as $key => $value) {
            if (! in_array($key, $this->fillable) && ! in_array($key, config('translatable.locales'))) {
                $attributes['value'][$key] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Update model.
     *
     * @param  array $attributes
     * @param  array $options
     * @return void
     */
    public function update(array $attributes = [], array $options = [])
    {
        return parent::update(
            $this->prepareAttributesForSave($attributes),
            $options
        );
    }

    /**
     * Get a relationship.
     *
     * @param  string $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (method_exists($this, $key) || in_array($key, $this->fieldIds)) {
            return $this->getRelationshipFromMethod($key);
        }
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        $attributes['value'] = $attributes['value'] ?? [];

        foreach (($this->fields ?? []) as $field) {
            if ($field->translatable) {
                continue;
            }
            $attributes['value'][$field->local_key] = $this->getFormattedFieldValue($field);
        }

        // For not translated fields.
        return array_merge($attributes['value'], $attributes);
    }

    /**
     * Get attribute.
     *
     * @param  string $key
     * @return void
     */
    public function getAttribute($key)
    {
        // Using fieldIds instead of fieldExists to avoid infinite loop
        // when calling getAttribute({field_id}).
        if (! in_array($key, $this->fieldIds)) {
            return parent::getAttribute($key);
        }

        return $this->getFormattedFieldValue(
            $this->findField($key)
        );
    }

    /**
     * Set field ids to be able to check if field exists in getAttribute method.
     *
     * @param  array $ids
     * @return void
     */
    public function setFieldIds(array $ids)
    {
        $this->fieldIds = $ids;
    }

    /**
     * Get field ids.
     *
     * @return array
     */
    public function getFieldIds()
    {
        return $this->fieldIds;
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array       $attributes
     * @param  string|null $connection
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        static::setModelFieldIds(
            $model = parent::newFromBuilder($attributes, $connection)
        );

        return $model;
    }

    /**
     * Set config_type attribute.
     *
     * @param  string $value
     * @return void
     */
    public function setConfigTypeAttribute($value)
    {
        $this->attributes['config_type'] = $value;

        static::setModelFieldIds($this);
    }

    /**
     * Set field ids to be able to check if field exists in getAttribute method.
     *
     * @param  self $model
     * @return void
     */
    protected static function setModelFieldIds(self $model)
    {
        if (! $model->config_type) {
            return;
        }

        if (! empty($model->fieldIds)) {
            return;
        }

        $model->setFieldIds($model->fields->map(function ($field) {
            return $field->id;
        })->toArray());
    }

    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $attributes = parent::relationsToArray();

        foreach (($this->fields ?? []) as $field) {
            if (! $field instanceof RelationField) {
                continue;
            }

            $value = $this->getFormattedFieldValue($field);

            if ($value instanceof EloquentCollection) {

                // Adding "first_..." to the attributes. This allows to show the
                // first image of a collection for example in a repeatable preview.
                $attributes["first_{$field->id}"] = $value ? $value->first() : null;
            }

            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            }

            $attributes[$field->id] = $value;
        }

        return $attributes;
    }

    /**
     * Modified to return relation instances for relation fields.
     *
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (! in_array($method, $this->fieldIds)) {
            return parent::__call($method, $params);
        }

        $field = $this->findField($method);

        if (! $field instanceof RelationField) {
            return parent::__call($method, $params);
        }

        return $field->getRelationQuery($this);
    }
}
