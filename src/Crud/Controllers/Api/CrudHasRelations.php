<?php

namespace Fjord\Crud\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Fjord\Crud\Models\FormRelation;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\MorphToMany;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;
use Fjord\Crud\Fields\Relations\BelongsToMany;

trait CrudHasRelations
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

        $field = $this->config->form->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        return $field->getQuery()->get();
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

        $field = $this->config->form->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        $relation = $field->getQuery()->findOrFail($relation_id);

        if ($field instanceof OneRelation || $field instanceof ManyRelation) {
            return $this->destroyFormRelation($request, $field, $model, $relation);
        }
        if ($field instanceof BelongsToMany) {
            return $this->destroyBelongsToMany($request, $field, $model, $relation);
        }
        if ($field instanceof MorphToMany) {
            return $this->destroyMorphToMany($request, $field, $model, $relation);
        }

        abort(405);
    }


    /**
     * Remove morphToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyMorphToMany(CrudUpdateRequest $request, MorphToMany $field, $model, $relation)
    {
        $morphToMany = $field->relation($model, $query = true);
        return DB::table($morphToMany->getTable())->where([
            $morphToMany->getRelatedPivotKeyName() => $relation->id,
            $morphToMany->getForeignPivotKeyName() => $model->id,
            $morphToMany->getMorphType() => get_class($relation)
        ])->delete();
    }

    /**
     * Remove BelongsToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param BelongsToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyBelongsToMany(CrudUpdateRequest $request, BelongsToMany $field, $model, $relation)
    {
        $belongsToMany = $field->relation($model, $query = true);
        $table = $belongsToMany->getTable();
        return DB::table($table)->where([
            $belongsToMany->getForeignPivotKeyName() => $model->id,
            $belongsToMany->getRelatedPivotKeyName() => $relation->id
        ])->delete();
    }

    /**
     * Remove oneRelation relation.
     *
     * @param CrudUpdateRequest $request
     * @param OneRelation|ManyRelation $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyFormRelation(CrudUpdateRequest $request, $field, $model, $relation)
    {
        $query = [
            'from_model_type' => $this->model,
            'from_model_id' => $model->id,
            'to_model_type' => get_class($relation),
            'to_model_id' => $relation->id,
            'field_id' => $field->id
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

        $field = $this->config->form->findField($relation) ?? abort(404);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        $relation = $field->getQuery()->findOrFail($relation_id);

        if ($field instanceof OneRelation) {
            return $this->createOneRelation($request, $field, $model, $relation);
        }
        if ($field instanceof ManyRelation) {
            return $this->createManyRelation($request, $field, $model, $relation);
        }
        if ($field instanceof BelongsToMany) {
            return $this->createBelongsToMany($request, $field, $model, $relation);
        }
        if ($field instanceof MorphToMany) {
            return $this->createMorphToMany($request, $field, $model, $relation);
        }

        abort(405);
    }

    /**
     * Create MorphToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function createMorphToMany(CrudUpdateRequest $request, MorphToMany $field, $model, $relation)
    {
        $morphToMany = $field->relation($model, $query = true);
        return DB::table($morphToMany->getTable())->insert([
            $morphToMany->getRelatedPivotKeyName() => $relation->id,
            $morphToMany->getForeignPivotKeyName() => $model->id,
            $morphToMany->getMorphType() => get_class($relation)
        ]);
    }

    /**
     * Add BelongsToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param BelongsToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function createBelongsTo(CrudUpdateRequest $request, BelongsToMany $field, $model, $relation)
    {
        $belongsToMany = $field->relation($model, $query = true);
        return DB::table($belongsToMany->getTable())->insert([
            $belongsToMany->getForeignPivotKeyName() => $model->id,
            $belongsToMany->getRelatedPivotKeyName() => $relation->id
        ]);
    }

    /**
     * Add BelongsToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param BelongsToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function createBelongsToMany(CrudUpdateRequest $request, BelongsToMany $field, $model, $relation)
    {
        $belongsToMany = $field->relation($model, $query = true);
        return DB::table($belongsToMany->getTable())->insert([
            $belongsToMany->getForeignPivotKeyName() => $model->id,
            $belongsToMany->getRelatedPivotKeyName() => $relation->id
        ]);
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
            'field_id' => $field->id
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
            'field_id' => $field->id
        ];

        // Check if relation already exists.
        if (FormRelation::where($query)->exists()) {
            abort(404);
        }

        FormRelation::create($query);

        return $relation;
    }

    /**
     * Order relations.
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
        $field = $this->config->form->findField($relation) ?? abort(404);

        $relations = $model->$relation()->get();

        return $this->order($relations, $field, $ids);
    }
}
