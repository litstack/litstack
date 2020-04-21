<?php

namespace Fjord\Crud\Models;

use Fjord\EloquentJs\CanEloquentJs;
use Fjord\Crud\Fields\Blocks\Blocks;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class FormBlock extends Model implements HasMedia, TranslatableContract
{
    use HasMediaTrait,
        Translatable,
        Concerns\HasConfig,
        Concerns\HasFields,
        Concerns\HasMedia;

    protected $translationModel = Translations\FormBlockTranslation::class;

    public $timestamps = false;
    public $translatedAttributes = ['value'];
    protected $fieldIds = [];

    protected $fillable = ['field_id', 'model_type', 'model_id', 'type', 'content', 'order_column'];
    protected $appends = ['fields', 'translation'];
    protected $casts = ['value' => 'json'];

    /**
     * Model relation.
     *
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
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
     * Get config key from model relation.
     *
     * @return string
     */
    public function getConfigKey()
    {
        if ($this->model instanceof FormField) {
            return $this->model->getConfigKey();
        }

        return "crud." . lcfirst(class_basename(get_class($this->model)));
    }

    /**
     * Get fields from config.
     *
     * @return Field
     */
    public function getFieldsAttribute()
    {
        $fields = $this->config->form->getRegisteredFields();

        foreach ($fields as $field) {
            if ($field instanceof Blocks && $field->id == $this->field_id) {

                // Returning fields from repeatables form.
                return $field->repeatables->{$this->type}->getRegisteredFields();
            }
        }
    }

    /**
     * Get translation attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTranslationAttribute()
    {
        return collect($this->getTranslationsArray())->map(function ($item) {
            return array_pop($item);
        })->toArray();
    }

    /**
     * Update FormBlock.
     *
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function update(array $attributes = array(), array $options = array())
    {
        foreach (config('translatable.locales') as $locale) {
            if (!array_key_exists($locale, $attributes)) {
                continue;
            }
            $attributes[$locale] = ['value' => $attributes[$locale]];
        }

        return parent::update($attributes, $options);
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
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->fields as $field) {
            foreach ($attributes['translation'] as $locale => $values) {
                $attributes['translation'][$locale][$field->id] = $this->getFormattedFieldValue($field);
            }
        }

        return $attributes;
    }

    /**
     * Get an attribute array of all arrayable relations.
     *
     * @return array
     */
    protected function getArrayableRelations()
    {
        $items = $this->getArrayableItems($this->relations);

        // Removing model relation from arrayable items to avoid infinite loop.
        unset($items['model']);

        return $items;
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
}
