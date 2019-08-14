<?php

namespace AwStudio\Fjord\Http\Controllers;

use AwStudio\Fjord\Http\Controllers\FjordController;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\ModelContent;
use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;
use AwStudio\Fjord\Models\Relation;
use Exception;

class FjordRelationsController extends FjordController
{
    public function index(Request $request)
    {
        $model = with(new $request->model_type);
        if($request->model_id) {
            $model = $request->model_type::where('id', $request->model_id)->first();
        }

        $field = $model->findField($request->id);

        return $field['query']->get();
    }

    public function updateHasOne(Request $request)
    {
        $model = $request->model::findOrFail($request->id);
        $model->{$request->key} = $request->value;
        $model->save();
        return $model;
    }

    public function store(Request $request)
    {
        $data = Relation::create($request->all());

        return $data;
    }

    public function delete($index)
    {
        $item = Relation::skip($index)->first();
        $item->delete();
    }
}
