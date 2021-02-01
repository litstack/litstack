<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Repositories\Relations\MorphManyRepository;

class MorphMany extends ManyRelationField
{
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'morphMany',
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
        'form',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    public $defaultAttributes = [];

    /**
     * Repository class.
     *
     * @var string
     */
    protected $repository = MorphManyRepository::class;
}
