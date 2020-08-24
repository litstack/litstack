<?php

namespace Lit\Crud\Models;

use Illuminate\Database\Eloquent\Model;
use Lit\Crud\Models\Traits\TrackEdits;
use Lit\Support\Facades\Config;

class Form extends LitFormModel
{
    use TrackEdits;

    /**
     * Database table name.
     *
     * @var string
     */
    public $table = 'lit_forms';

    /**
     * Translation model class.
     *
     * @var string
     */
    protected $translationModel = Translations\FormTranslation::class;

    /**
     * Translation foreign key.
     *
     * @var string
     */
    protected $translationForeignKey = 'lit_form_id';

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
