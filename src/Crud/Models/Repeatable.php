<?php

namespace Lit\Crud\Models;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;
use Lit\Crud\Fields\Block\Block;

class Repeatable extends LitFormModel
{
    use ForwardsCalls;

    public $table = 'lit_repeatables';

    /**
     * Translation model.
     *
     * @var string
     */
    protected $translationModel = Translations\RepeatableTranslation::class;

    /**
     * Translation foreign key.
     *
     * @var string
     */
    protected $translationForeignKey = 'lit_repeatable_id';

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
        'type',
        'content',
        'order_column',
        'value',
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
     * Get form.
     *
     * @return BaseForm|null
     */
    public function getForm()
    {
        if (! $config = $this->config) {
            return;
        }

        return $config->{$this->getFormType()};
    }

    /**
     * Get view name.
     *
     * @return string|null
     */
    public function getViewAttribute()
    {
        return $this->getRepeatable()->getView();
    }

    /**
     * Get fields from config.
     *
     * @return Collection
     */
    public function getFieldsAttribute(): Collection
    {
        if (! $repeatable = $this->getRepeatable()) {
            return collect([]);
        }

        return $repeatable->getRegisteredFields();
    }

    /**
     * Get repeatable.
     *
     * @return Repeatables
     */
    public function getRepeatable()
    {
        $fields = $this->getForm()->getRegisteredFields();

        foreach ($fields as $field) {
            if (! $field instanceof Block) {
                continue;
            }

            if ($field->id == $this->field_id) {

                // Returning fields from repeatables form.
                return $field->repeatables->{$this->type};
            }

            foreach ($field->getRegisteredFields() as $blockField) {
                if ($blockField instanceof Block && $blockField->id == $this->field_id) {
                    return $blockField->repeatables->{$this->type};
                }
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
     * Modified calls.
     *
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    public function __call($method, $params = [])
    {
        try {
            return parent::__call($method, $params);
        } catch (BadMethodCallException $e) {
            return $this->forwardCallTo($this->getRepeatable(), $method, $params);
        }
    }
}
