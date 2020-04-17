<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Models\FormField;
use Fjord\Crud\OneRelationField;

class OneRelation extends OneRelationField
{
    use Concerns\ManagesRelation;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-relation-one';

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'oneRelation'
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
        'preview',
        'confirm'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'confirm' => true
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
        $relation = $model->oneRelation($this->relationModel);

        return $model->oneRelation($this->relationModel);
    }
}
