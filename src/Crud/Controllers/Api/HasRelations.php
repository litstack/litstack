<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Models\FormRelation;
use Fjord\Form\Requests\CrudReadRequest;
use Fjord\Form\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\OneRelation;

trait HasRelations
{
    /**
     * Load relation index.
     *
     * @param CrudReadRequest $request
     * @param int $id
     * @param string $relation
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function relationIndex(CrudReadRequest $request, $id, $relation)
    {
        $model = $this->model::findOrFail($id);

        $field = $model->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        return $field->query()->get();
    }

    /**
     * Remove relation.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $relation
     * @param int $relation_id
     * @return mixed
     */
    public function destroyRelation(CrudUpdateRequest $request, $id, $relation, $relation_id)
    {
    }

    /**
     * Add new relation.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $relation
     * @param int $relation_id
     * @return mixed
     */
    public function createRelation(CrudUpdateRequest $request, $id, $relation, $relation_id)
    {
        $model = $this->model::findOrFail($id);

        $field = $model->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        $relation = $field->query()->findOrFail($relation_id);

        if ($field instanceof OneRelation) {
            return $this->createOneRelation($request, $field, $model, $relation);
        }
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
            'from_model_type' => $this->model,
            'from_model_id' => $model->id,
            'to_model_type' => get_class($relation),
            'to_model_id' => $relation->id
        ];

        // Replace previous relation with new one.
        FormRelation::where($query)->delete();
        FormRelation::create($query);

        return $relation;
    }
}
