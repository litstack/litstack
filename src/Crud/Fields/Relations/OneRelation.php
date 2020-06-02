<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Fields\Traits\HasBaseField;

class OneRelation extends OneRelationField
{
    use Concerns\ManagesFjordRelationField,
        HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'oneRelation'
    ];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['model'];

    /**
     * Get relation for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        if (method_exists($model, $this->id)) {
            return parent::getRelationQuery($model);
        }

        return $this->modifyQuery(
            $model->oneRelation($this->relatedModelClass, $this->id)
        );
    }
}
