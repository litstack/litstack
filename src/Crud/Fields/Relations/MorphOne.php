<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\OneRelationField;

class MorphOne extends OneRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'morphOne'
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
        'sortable',
        'orderColumn',
        'filter',
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
        'sortable' => false,
        'orderColumn' => 'order_column',
        'relatedCols' => 12,
        'small' => false,
        'perPage' => 1,
        'searchable' => false,
    ];

    /**
     * Set relation attributes.
     *
     * @param mixed $relation
     * @return self
     */
    protected function setRelationAttributes($relation)
    {
        return;
        if (!$this->getModelConfig($this->related)) {
            $this->throwMissingConfigException();
        }

        $this->attributes['foreign_key'] = $relation->getForeignKeyName();
        $this->attributes['morph_type_value'] = $this->model;
        $this->attributes['morph_type'] = $relation->getMorphType();
        $this->attributes['local_key_name'] = $relation->getLocalKeyName();
    }
}
