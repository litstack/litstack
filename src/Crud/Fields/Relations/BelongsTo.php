<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class BelongsTo extends OneRelationField
{
    use FormItemWrapper;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'belongsTo'
    ];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $requiredAttributes = [
        'title',
        'model',
        'preview'
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    public $availableAttributes = [
        'model',
        'form',
        'previewQuery',
        'preview',
        'confirm',
        'filter',
        'relatedCols',
        'small',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [
        'confirm' => false,
        'relatedCols' => 12,
        'small' => false,
        'perPage' => 1,
        'searchable' => false,
    ];

    /**
     * Set relation attributes.
     *
     * @param string $model
     * @return self
     */
    protected function setRelationAttributes($relation)
    {
        $this->attributes['local_key'] = $relation->getForeignKeyName();
    }
}