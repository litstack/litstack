<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Models\FormField;
use Fjord\Crud\ManyRelationField;

class ManyRelation extends ManyRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-relation-many';

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'manyRelation'
    ];

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
        'model',
        'index'
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
        'index',
        'confirm',
        'sortable',
        'orderColumn'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'confirm' => false,
        'sortable' => true,
        'orderColumn' => 'order_column'
    ];

    /**
     * Get relation for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    protected function getRelation($model)
    {
        if (!$model instanceof FormField) {
            return parent::getRelation($model);
        }

        return $model->manyRelation($this->relationModel);
    }
}
