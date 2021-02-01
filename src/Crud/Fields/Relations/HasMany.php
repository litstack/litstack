<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Repositories\Relations\HasManyRepository;

class HasMany extends ManyRelationField
{
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'hasMany',
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    public $requiredAttributes = [
        'preview',
    ];

    /**
     * Available field attributes.
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
    protected $repository = HasManyRepository::class;

    /**
     * Set relation attributes.
     *
     * @param string $model
     *
     * @return self
     */
    protected function setRelationAttributes($relation)
    {
        $this->attributes['foreign_key'] = $relation->getForeignKeyName();
    }
}
