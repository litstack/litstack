<?php

namespace Fjord\Form\Controllers\Traits;

use Illuminate\Http\Request;

trait CrudRelations
{
    public function relationIndex(Int $id, $relation)
    {
        $className = $this->model;
        $model = new $className();

        $relations = $model->find($id)->$relation()->get();

        $eloquentModels = [];
        foreach ($relations as $relation) {
            $eloquentModels[]=($relation->eloquentJs('fjord'));
        }
        return $eloquentModels;
    }

    public function relationStore(Int $id, $relation)
    {
        $className = $this->model;
        $model = new $className();


        $foreign_key = $this->getForm($model)->form_fields->firstWhere('id', $relation)->foreign_key;

        $relationName = $model->find($id)->$relation();

        $relationClassName = get_class($relationName->getRelated());
        $relation = new $relationClassName();

        $relation[$foreign_key] = $id;

        return $relation->eloquentJs('fjord');
    }

    public function relationRemove(Int $id, $relation, Int $foreign_id)
    {
        $className = $this->model;
        $model = new $className();

        $foreign_key = $this->getForm($model)->form_fields->firstWhere('id', $relation)->foreign_key;

        $relationName = $model->find($id)->$relation();

        $relationClassName = get_class($relationName->getRelated());
        $relation = new $relationClassName();

        $relation = $relation::find($foreign_id)->update([
            $foreign_key => null
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function relationDestroy(Int $id, $relation, Int $foreign_id)
    {
        $className = $this->model;
        $model = new $className();

        $foreign_key = $this->getForm($model)->form_fields->firstWhere('id', $relation)->foreign_key;

        $relationName = $model->find($id)->$relation();

        $relationClassName = get_class($relationName->getRelated());
        $relation = new $relationClassName();

        $relation = $relation::find($foreign_id)->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function unrelatedRelation(Request $request)
    {
        $className = $request->model;
        $model = new $className();

        $relations = $model->where($request->foreign_key,  '')
                           ->orWhereNull($request->foreign_key)
                           ->get();

        $eloquentModels = [];
        foreach ($relations as $relation) {
            $eloquentModels[]=($relation->eloquentJs('fjord'));
        }
        return $eloquentModels;
    }

    public function relationLink(Request $request)
    {
        $className = $request->model;
        $model = new $className();
        
        $relations = $model->find($request->id)->update([
            $request->foreign_key => $request->foreign_id
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
