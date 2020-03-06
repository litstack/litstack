<?php

namespace AwStudio\Fjord\Form\Controllers;

use AwStudio\Fjord\Fjord\Controllers\FjordController;
use Illuminate\Http\Request;

class FormMorphOneController extends FjordController
{
    public function index(Request $request)
    {
        $model = with(new $request->model_type);

        $field = $model->findFormField($request->id);

        return [
            'model' => get_class($field['querys'][$request->model_name]->getModel()),
            'rows' => $field['querys'][$request->model_name]->get()
        ];
    }

    public function store(Request $request)
    {
        $model = with(new $request->model);
        $model = $model::findOrFail($request->id);

        $model->update([
            "{$request->morph}_type" =>  $request->morph_model,
            "{$request->morph}_id" =>  $request->morph_id
        ]);

        return $model;
    }

    // TODO: add missing methods

}
