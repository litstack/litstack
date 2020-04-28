<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Fjord\Crud\Models\FormRelation;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\ManyRelation;

trait ManagesManyRelation
{
    /**
     * Add manyRelation relation.
     *
     * @param CrudUpdateRequest $request
     * @param ManyRelation $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function createManyRelation(CrudUpdateRequest $request, ManyRelation $field, $model, $relation)
    {
        $query = [
            'from_model_type' => get_class($model),
            'from_model_id' => $model->id,
            'to_model_type' => get_class($relation),
            'to_model_id' => $relation->id,
            'field_id' => $field->id
        ];

        // Check if relation already exists.
        if (FormRelation::where($query)->exists()) {
            abort(404);
        }

        FormRelation::create($query);
    }

    /**
     * Add manyRelation relation.
     *
     * @param CrudUpdateRequest $request
     * @param ManyRelation $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function destroyManyRelation(CrudUpdateRequest $request, ManyRelation $field, $model, $relation)
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
}
