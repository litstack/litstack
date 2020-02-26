<?php

namespace AwStudio\Fjord\Form\Controllers;

use AwStudio\Fjord\Fjord\Controllers\FjordController;
use AwStudio\Fjord\Form\FormLoader;
use Illuminate\Http\Request;

class FormBelongsToManyController extends FjordController
{
    public function index(Request $request)
    {
        $model = new $request->model;
        $foreign = $request->foreign;

        $related = $model->find($request->id)->$foreign()->get();

        return $related;
    }

    public function relations(Request $request)
    {
        $model = new $request->model;
        return $model->all();
    }

    public function store(Request $request)
    {
        $model = new $request->model;
        $foreign = $request->foreign;

        $model->find($request->id)->$foreign()->attach($request->foreign_id);

        $related = $model->find($request->id)->$foreign()->find($request->foreign_id);

        return $related;
    }


}
