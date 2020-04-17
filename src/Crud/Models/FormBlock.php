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
        CanEloquentJs,
        Traits\HasConfig,
        Traits\HasFields;

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
     * Get config key from model relation.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return $this->model->getConfigKey();
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

    public function field_relations($form_field)
    {
        return $this->formMany($form_field->model ?? '');
    }

    public function field_relation($form_field)
    {
        return (new Relations\EmptyRelation($form_field->query, $this))
            ->where('id', $this->getTranslatedFormFieldValue($form_field));
    }

    public function getFieldRelation($form_field, $getQuery = false)
    {
        if ($form_field->many) {
            return $getQuery
                ? $this->form_field_relations($form_field)
                : $this->form_field_relations($form_field)->get();
        }

        return $getQuery
            ? $this->form_field_relation($form_field)
            : $this->form_field_relation($form_field)->first();
    }

    public function getTranslationAttribute()
    {
        return collect($this->getTranslationsArray())->map(function ($item) {
            return array_pop($item);
        })->toArray();
    }

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

    public function getTranslatedFormFieldValue($form_field)
    {
        if ($form_field->translatable) {
            $values = $this->translation[app()->getLocale()] ?? [];
        } else {
            $values = $this->translation[config('translatable.fallback_locale')] ?? [];
        }

        return $values[$form_field->id] ?? null;
    }

    /**
     * Get attribute.
     *
     * @param string $key
     * @return void
     */
    public function getAttribute($key)
    {
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
