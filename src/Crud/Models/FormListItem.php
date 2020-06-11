<?php

namespace Fjord\Crud\Models;

use Fjord\Crud\Fields\ListField;
use Fjord\Crud\Fields\Block\Block;
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
     * @var boolean
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
        'value' => 'json'
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
}
