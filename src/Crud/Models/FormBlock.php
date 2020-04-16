<?php

namespace Fjord\Crud\Models;

use Fjord\EloquentJs\CanEloquentJs;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Support\Facades\FormLoader;
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

    public function getFormFieldsAttribute()
    {
        $fields = clone FormLoader::loadFields($this->form_fields_path, $this);

        return $this->getDynamicFieldValues($fields);
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

    public function toArray()
    {
        $array = parent::toArray();

        foreach ($this->form_fields as $form_field) {

            if (!in_array($form_field->type, ['boolean', 'relation', 'image', 'checkboxes'])) {
                continue;
            }

            $value = $this->getFormattedFormFieldValue($form_field, false, false);

            if (in_array($form_field->type, ['checkboxes', 'boolean'])) {

                // Formated casts (json, array, boolean, etc...) must be put back to
                // their original place.
                $array['translation'][config('translatable.fallback_locale')][$form_field->id] = $value;
            } else {

                // For relations add $form_field->id as array_key and set the
                // relation as value.
                $array[$form_field->id] = $value;
            }
            //$array[$form_field->id] = $this->getMedia($form_field->id)->toArray();
        }

        return $array;
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
        if (array_key_exists($key, $this->attributes)) {
            return parent::getAttribute($key);
        }

        if (!$this->fieldExists($key)) {
            return parent::getAttribute($key);
        }

        return $this->getFormattedFieldValue(
            $this->findField($key)
        );
    }
}
