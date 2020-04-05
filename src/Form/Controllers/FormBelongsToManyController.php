<?php

namespace Fjord\Form\Controllers;

use Fjord\Fjord\Controllers\FjordController;
use Fjord\Support\Facades\FormLoader;
use Illuminate\Http\Request;

class FormBelongsToManyController extends FjordController
{
    public function relations(Request $request)
    {
        $model = new $request->model;
        $foreign = $request->foreign;

        $related = $model->find($request->id)->$foreign()->get();

        return $related;
    }

    public function index(Request $request)
    {
        $model = with(new $request->model);

        $fields = require $model->form_fields_path;

        $query = collect($fields['form_fields'])->flatten(1)->firstWhere('id', $request->field['id'])['model'];

        if(is_string($query)){
            $query = new $query;
        }

        return $query->get();
    }

    public function update(Request $request)
    {
        $model = new $request->model;
        $foreign = $request->foreign;

        $result = $model->find($request->id)->$foreign()->toggle([$request->foreign_id]);

        return $result;
    }
}
