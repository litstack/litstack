<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Models\FormRelation;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;

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
        $model = $this->query()->findOrFail($id);

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
        $model = $this->query()->findOrFail($id);

        $field = $model->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        $relation = $field->query()->findOrFail($relation_id);

        if ($field instanceof OneRelation) {
            return $this->destroyOneRelation($request, $field, $model, $relation);
        }
    }

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
            'from_model_type' => $this->model,
            'from_model_id' => $model->id,
            'to_model_type' => get_class($relation),
            'to_model_id' => $relation->id
        ];

        return FormRelation::where($query)->delete();
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
        $model = $this->query()->findOrFail($id);

        $field = $model->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        $relation = $field->query()->findOrFail($relation_id);

        if ($field instanceof OneRelation) {
            return $this->createOneRelation($request, $field, $model, $relation);
        }
        if ($field instanceof ManyRelation) {
            return $this->createManyRelation($request, $field, $model, $relation);
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
        ];

        // Replace previous relation with new one.
        FormRelation::where($query)->delete();
        $query['to_model_id'] = $relation->id;
        FormRelation::create($query);

        return $relation;
    }

    /**
     * Add oneRelation relation.
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
            'from_model_type' => $this->model,
            'from_model_id' => $model->id,
            'to_model_type' => get_class($relation),
            'to_model_id' => $relation->id,
        ];

        // Check if relation already exists.
        if (FormRelation::where($query)->exists()) {
            abort(404);
        }

        FormRelation::create($query);

        return $relation;
    }

    /**
     * Order relation.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $relation
     * @return void
     */
    public function orderRelation(CrudUpdateRequest $request, $id, $relation)
    {
        $ids = $request->ids ?? abort(404);
        $model = $this->query()->findOrFail($id);
        $field = $model->findField($relation) ?? abort(404);

        $relations = $model->$relation()->get();

        foreach ($ids as $order => $id) {
            $relation = $relations->where('id', $id)->first();

            if (!$relation) {
                continue;
            }
            if ($relation->pivot) {
                $relation->pivot->{$field->orderColumn} = $order;
                $relation->pivot->save();
            } else {
                $relation->{$field->orderColumn} = $order;
                $relation->save();
            }
        }
    }
}
