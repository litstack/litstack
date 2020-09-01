<?php

namespace Ignite\Crud\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends LitFormModel
{
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
        'translations', 'media',
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
