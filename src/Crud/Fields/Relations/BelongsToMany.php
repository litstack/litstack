<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\ManyRelationField;

class BelongsToMany extends ManyRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'belongsToMany'
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
        'preview',
        'confirm',
        'sortable',
        'orderColumn',
        'query'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'confirm' => false,
        'sortable' => false,
        'orderColumn' => 'order_column'
    ];
}
