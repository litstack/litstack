<?php

namespace Lit\Crud\Fields\Relations;

use Lit\Crud\Fields\Traits\HasBaseField;

class OneRelation extends OneRelationField
{
    use Concerns\ManagesLitRelationField;
    use HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'oneRelation',
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
     * @param bool  $query
     *
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        if (method_exists($model, $this->id)) {
            return parent::getRelationQuery($model);
        }

        return $this->modifyQuery(
            $model->oneRelation($this->getRelatedModelClass(), $this->id)
        );
    }
}
