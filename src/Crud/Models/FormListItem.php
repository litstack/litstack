<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\Fields\ListField\ListCollection;
use Fjord\Crud\Fields\ListField\ListField;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FormListItem extends FjordFormModel
{
    /**
     * Translation model.
     *
     * @var string
     */
    protected $translationModel = Translations\FormListItemTranslation::class;

    /**
     * No timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'form_type',
        'config_type',
        'field_id',
        'model_type',
        'model_id',
        'parent_id',
        'value',
        'order_column',
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
     * Appends.
     *
     * @var array
     */
    protected $appends = ['fields', 'translation'];

    /**
     * Eager loads.
     *
     * @var array
     */
    protected $with = ['translations', 'media'];

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
     * Children relation.
     *
     * @return void
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Parent relation.
     *
     * @return void
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get fields from config.
     *
     * @return Field
     */
    public function getFieldsAttribute()
    {
        $fields = $this->getForm()->getRegisteredFields();

        foreach ($fields as $field) {
            if ($field instanceof ListField && $field->id == $this->field_id) {
                // Returning fields from repeatables form.
                return $field->getRegisteredFields();
            }
        }
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
     * Create a new Eloquent Collection instance.
     *
     * @param array $models
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new ListCollection($models);
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();

        // Removing [depth] attribute to avoid exception when saving since depth
        // column does not exist in database.
        if (array_key_exists('depth', $attributes)) {
            unset($attributes['depth']);
        }

        return $attributes;
    }

    /**
     * Boot FormListItem.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($model) {
            foreach ($model->children as $child) {
                $child->delete();
            }
        });
    }
}
