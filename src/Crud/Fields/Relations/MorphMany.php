<?php

namespace Fjord\Crud\Fields\Relations;

class MorphMany extends ManyRelationField
{
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
        'form',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [];
}