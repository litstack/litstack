<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\Models\FormEdit;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Fjord\Crud\Models\Traits\TrackEdits;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class FormField extends FjordFormModel
{
    use TrackEdits;

    /**
     * Translation model class.
     *
     * @var string
     */
    protected $translationModel = Translations\FormFieldTranslation::class;

    /**
     * value is translatable but since non translatable fields are stored in 
     * the value field it is important to not set value as a translatedAttribute 
     * here, because the translator would store it to the fallback locale.
     *
     * @var array
     */
    public $translatedAttributes = [];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = [
        'translation',
    ];

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
        'media',
        'last_edit'
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
}
