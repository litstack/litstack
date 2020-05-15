<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Fjord\Crud\Models\FormRelation;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\OneRelation;

trait ManagesOneRelation
{
    /**
     * Remove oneRelation relation.
     *
     * @param CrudUpdateRequest $request
     * @param OneRelation $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyOneRelation(CrudUpdateRequest $request, OneRelation $field, $model, $relation)
    {
        $query = [
            'from_model_type' => get_class($model),
            'from_model_id' => $model->id,
            'to_model_type' => get_class($relation),
            'to_model_id' => $relation->id,
            'field_id' => $field->id
        ];

        FormRelation::where($query)->delete();
    }

    /**
     * Add oneRelation relation.
     *
     * @param CrudUpdateRequest $request
     * @param OneRelation $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function createOneRelation(CrudUpdateRequest $request, OneRelation $field, $model, $relation)
    {
        $query = [
            'from_model_type' => get_class($model),
            'from_model_id' => $model->id,
            'to_model_type' => get_class($relation),
            'field_id' => $field->id
        ];

        // Replace previous relation with new one.
        FormRelation::where($query)->delete();
        $query['to_model_id'] = $relation->id;
        FormRelation::create($query);

        return $relation;
    }
}
