<?php

namespace AwStudio\Fjord\Form\Controllers;

use AwStudio\Fjord\Fjord\Controllers\FjordController;
use AwStudio\Fjord\Form\FormLoader;
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
        $model = new $request->model;
        return $model->all();
    }

    public function update(Request $request)
    {
        $model = new $request->model;
        $foreign = $request->foreign;

        $result = $model->find($request->id)->$foreign()->toggle([$request->foreign_id]);

        return $result;
    }
}
