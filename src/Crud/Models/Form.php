<?php

namespace Ignite\Crud\Models;

use Illuminate\Database\Eloquent\Model;
use Ignite\Crud\Models\Traits\TrackEdits;

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
}
