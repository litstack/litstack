<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\ManyRelationField;

class MorphMany extends ManyRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'morphMany'
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
        'query',
        'relatedCols',
        'small',
        'perPage',
        'searchable',
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
        'perPage' => 10,
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
    }
}
