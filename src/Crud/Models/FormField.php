<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\Models\FormEdit;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class FormField extends FjordFormModel
{
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
     * Appends.
     *
     * @var array
     */
    protected $appends = [
        'translation'
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
}
