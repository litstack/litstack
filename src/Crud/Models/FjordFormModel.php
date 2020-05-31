<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\MediaField;
use Fjord\Crud\RelationField;
use Spatie\MediaLibrary\HasMedia;
use Fjord\Crud\Fields\Media\Image;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Fjord\Crud\Fields\Relations\ManyRelationField;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

//class FjordFormModel extends Model implements HasMedia, TranslatableContract
class FjordFormModel extends Model implements HasMedia, TranslatableContract
{

    use Traits\HasMedia,
        Translatable,
        Concerns\HasConfig,
        Concerns\HasFields,
        Concerns\HasMedia;


    /**
     * value is translatable but since non translatable fields are stored in 
     * the value field it is important to not set value as a translatedAttribute 
     * here, because the translator would store it to the fallback locale.
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
     * Register media conversions for field.
     *
     * @param SpatieMedia $media
     * @return void
     */
    public function registerMediaConversions(SpatieMedia $media = null): void
    {
        $this->registerCrudMediaConversions($media);
    }

    /**
     * Get translation attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTranslationAttribute()
    {
        return $this->getTranslationsArray();
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
            if (!$translation) {
                continue;
            }
            $value = $translation->value ?? [];

            foreach ($this->fields as $field) {
                if (!$field->translatable) {
                    continue;
                }

                if (!array_key_exists($field->local_key, $value)) {
                    $value[$field->local_key] = $this->getFormattedFieldValue($field, $locale);
                }

                $translations[$translation->{$this->getLocaleKey()}] = $value;
            }
        }

        return $translations;
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

        $attributes['value'] = $this->value ?? [];
        foreach ($attributes as $key => $value) {
            if (!in_array($key, $this->fillable) && !in_array($key, config('translatable.locales'))) {
                $attributes['value'][$key] = $value;
            }
        }

        return parent::update($attributes, $options);
    }

    /**
     * Get field value.
     *
     * @param Field $field
     * @return mixed
     */
    public function getFieldValue($field, $locale = null)
    {
        if ($field instanceof RelationField) {
            return $field->getResults($this);
        }

        if (!$locale) {
            $locale = app()->getLocale();
        }

        if ($field->translatable) {
            return $this->getTranslatedFieldValue($field, app()->getLocale());
        }

        return $this->value[$field->local_key] ?? null;
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

        return $value[$field->local_key] ?? null;
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

        foreach ($this->fields as $field) {
            if ($field->translatable) {
                continue;
            }
            $attributes['value'][$field->local_key] = $this->getFormattedFieldValue($field);
        }

        // For not translated fields.
        $attributes = array_merge($attributes['value'], $attributes);

        return $attributes;
    }

    /**
     * Get attribute.
     *
     * @param string $key
     * @return void
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
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $attributes = parent::relationsToArray();

        foreach ($this->fields as $field) {
            if (!$field instanceof RelationField) {
                continue;
            }

            $attributes[$field->id] = $this->getFormattedFieldValue($field);

            if ($field instanceof ManyRelationField) {
                if ($field instanceof MediaField && $field->maxFiles == 1) {
                    continue;
                }
                $attributes["first_{$field->id}"] = $this->getFormattedFieldValue($field)->first();
            }
        }

        return $attributes;
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

        if (!$field instanceof RelationField) {
            return parent::__call($method, $params);
        }

        return $field->relation($this);
    }
}
