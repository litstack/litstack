<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class HasOne extends OneRelationField
{
    use FormItemWrapper;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'hasOne'
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
