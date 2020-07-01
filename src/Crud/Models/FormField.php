<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\Models\Traits\TrackEdits;
use Fjord\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

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
        'form_type',
        'config_type',
        'collection',
        'form_name',
        'field_id',
        'value',
        'field_type',
    ];

    /**
     * Eager loads.
     *
     * @var array
     */
    protected $with = [
        'translations',
        'media',
        'last_edit',
    ];

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Fix: config_type.
     */
    public function fixConfigType($model)
    {
        if ($model->collection && $model->form_name && ! $model->config_type) {
            $model->update([
                'config_type' => Config::getNamespaceFromKey("form.{$model->collection}.{$model->form_name}"),
            ]);
        }
    }
}
