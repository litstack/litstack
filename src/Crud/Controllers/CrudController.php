<?php

namespace Fjord\Crud\Controllers;

use Illuminate\Http\Request;

class CrudController
{
    use Api\HasIndex;

    public function index(Request $request)
    {
        $config = $this->model::config()
            ->except(
                'form',
                'controller',
                'model',
                'query',
                'indexQuery'
            );

        return view('fjord::app')
            ->withComponent('fj-new-crud-index')
            ->withProps([
                'config' => $config,
                'headerComponents' => [],
            ]);
    }

    public function create(Request $request)
    {
        $config = $this->model::config()->only('form', 'names', 'permissions');

        $model = new $this->model;

        return view('fjord::app')
            ->withComponent('fj-crud-create')
            ->withModels([
                'model' => $model->eloquentJs()
            ])
            ->withProps([
                'config' => $config,
            ]);
    }
}
