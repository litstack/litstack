<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Fields\Traits\HasBaseField;

class BelongsToMany extends ManyRelationField
{
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'belongsToMany',
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    public $requiredAttributes = [
        'title',
        'model',
        'preview',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    public $availableAttributes = [
        'title',
        'model',
        'form',
        'hint',
        'query',
        'preview',
        'confirm',
        'sortable',
        'filter',
        'relatedCols',
        'small',
        'perPage',
        'searchable',
        'tags',
        'tagVariant',
        'showTableHead',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [
        'confirm'     => false,
        'sortable'    => false,
        'relatedCols' => 12,
        'small'       => false,
        'perPage'     => 10,
        'searchable'  => false,
        'tags'        => false,
        'tagVariant'  => 'secondary',
    ];
}
