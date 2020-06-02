<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Fields\Traits\HasBaseField;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class ManyRelation extends ManyRelationField
{
    use Concerns\ManagesFjordRelationField,
        HasBaseField;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [
        'type' => 'manyRelation'
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
            $model->manyRelation($this->relatedModelClass, $this->id)
        );
    }
}
