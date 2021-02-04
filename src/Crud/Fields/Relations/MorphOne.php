<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Repositories\Relations\MorphOneRepository;

class MorphOne extends OneRelationField
{
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'morphOne',
    ];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $requiredAttributes = [];

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
    protected $repository = MorphOneRepository::class;
}
