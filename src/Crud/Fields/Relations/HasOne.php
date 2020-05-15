<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\OneRelationField;

class HasOne extends OneRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'hasOne'
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'model',
        'preview'
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'model',
        'hint',
        'form',
        'previewQuery',
        'preview',
        'confirm',
        'query',
        'relatedCols',
        'small',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
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
        $this->attributes['foreign_key'] = $relation->getForeignKeyName();
    }
}
