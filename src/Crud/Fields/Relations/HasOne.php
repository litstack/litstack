<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Repositories\Relations\HasOneRepository;

class HasOne extends OneRelationField
{
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'hasOne',
    ];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $requiredAttributes = [];

    /**
     * Repository class.
     *
     * @var string
     */
    protected $repository = HasOneRepository::class;

    /**
     * Set relation attributes.
     *
     * @param  string $model
     * @return self
     */
    protected function setRelationAttributes($relation)
    {
        $this->setAttribute('foreign_key', $relation->getForeignKeyName());
    }
}
