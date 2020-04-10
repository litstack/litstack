<?php

namespace Fjord\Form\Controllers\Traits;

use Illuminate\Support\Facades\DB;
use Fjord\Form\Database\FormRelation;
use Fjord\Form\Requests\CrudReadRequest;
use Fjord\Form\Requests\CrudUpdateRequest;

trait CrudRelations
{
    /**
     * Get relations for model.
     *
     * @param CrudReadRequest $request
     * @param [type] $id
     * @param [type] $relation
     * @return void
     */
    public function relationIndex(CrudReadRequest $request, $id, $relation)
    {
        $model = $this->model::findOrFail($id);

        return $model->$relation()->get();
    }

    public function orderRelation(CrudUpdateRequest $request, $id, $relation)
    {
        $ids = $request->ids;

        if (!$ids) {
            abort(404);
        }

        $model = $this->model::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relations = $model->$relation()->get();

        $relationInstance = $model->$relation();

        foreach ($ids as $order => $id) {
            //dd($relationInstance);
            $table = $relationInstance->getTable();
            //DB::table($table)->where('id', $id)->update('order_col');
            $relation = $relations->where('id', $id)->first();

            if (!$relation) {
                continue;
            }
            if ($relation->pivot) {
                $relation->pivot->order_column = $order;
                $relation->pivot->save();
            } else {
                $relation->order_column = $order;
                $relation->save();
            }
        }
    }

    public function deleteRelation(CrudUpdateRequest $request, $id, $relation, $relation_id)
    {
        // First we check if both crud model with the ID and relation model with 
        // the ID exist, no relations should be deleted for non existing records.
        $model = $this->model::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relationModel = $model->$relation()->findOrFail($relation_id);

        // Delete relation for form field "relation"
        if ($formField->type == 'relation') {
            return FormRelation::where('from_model_type', get_class($model))
                ->where('from_model_id', $id)
                ->where('to_model_type', get_class($relationModel))
                ->where('to_model_id', $relation_id)
                ->delete();
        }

        // Delete relation for form field "belongsToMany"
        if ($formField->type == 'belongsToMany') {
            $belongsToMany = $model->$relation();
            $table = $model->$relation()->getTable();
            return DB::table($table)->where([
                $belongsToMany->getForeignPivotKeyName() => $id,
                $belongsToMany->getRelatedPivotKeyName() => $relation_id
            ])->delete();
        }

        // Delete relation for form field "morphToMany"
        if ($formField->type == 'morphToMany') {
            $morphToMany = $model->$relation();
            $table = $model->$relation()->getTable();
            return DB::table($table)->where([
                $morphToMany->getRelatedPivotKeyName() => $relation_id,
                $morphToMany->getForeignPivotKeyName() => $id,
                $morphToMany->getMorphType() => $this->model
            ])->delete();
        }

        // Delete relation for form field "morphedByMany"
        if ($formField->type == 'morphedByMany') {
            $morphedByMany = $model->$relation();
            return DB::table($morphedByMany->getTable())->where([
                $morphedByMany->getRelatedPivotKeyName() => $relation_id,
                $morphedByMany->getForeignPivotKeyName() => $id,
                $morphedByMany->getMorphType() => $formField->model
            ])->delete();
        }
    }

    public function createRelation(CrudUpdateRequest $request, $id, $relation, $relation_id)
    {
        // First we check if both crud model with the ID and relation model with 
        // the ID exist, no relations should be created for non existing records.
        $model = $this->model::findOrFail($id);
        $formField = $model->findFormField($relation);
        if (!$formField) {
            abort(404);
        }
        $relationModel = $formField->query->findOrFail($relation_id);

        // Create relation for form field "relation"
        if ($formField->type == 'relation') {
            $query = [
                'from_model_type' => $this->model,
                'from_model_id' => $id,
                'to_model_type' => $formField->model,
                'to_model_id' => $relation_id,
            ];
            if (FormRelation::where($query)->exists()) {
                abort(422, __f("fj.already_selected"));
            }
            return FormRelation::create($query);
        }

        // Create relation for form field "belongsToMany"
        if ($formField->type == 'belongsToMany') {
            $belongsToMany = $model->$relation();
            return DB::table($belongsToMany->getTable())->insert([
                $belongsToMany->getForeignPivotKeyName() => $id,
                $belongsToMany->getRelatedPivotKeyName() => $relation_id
            ]);
        }

        // Create relation for form field "morphToMany"
        if ($formField->type == 'morphToMany') {
            $morphToMany = $model->$relation();
            return DB::table($morphToMany->getTable())->insert([
                $morphToMany->getRelatedPivotKeyName() => $relation_id,
                $morphToMany->getForeignPivotKeyName() => $id,
                $morphToMany->getMorphType() => $this->model
            ]);
        }

        // Create relation for form field "morphedByMany"
        if ($formField->type == 'morphedByMany') {
            $morphedByMany = $model->$relation();
            return DB::table($morphedByMany->getTable())->insert([
                $morphedByMany->getRelatedPivotKeyName() => $relation_id,
                $morphedByMany->getForeignPivotKeyName() => $id,
                $morphedByMany->getMorphType() => $formField->model
            ]);
        }
    }
}
