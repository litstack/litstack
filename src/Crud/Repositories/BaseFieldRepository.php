<?php

namespace Fjord\Crud\Repositories;

use Fjord\Crud\Field;
use Illuminate\Http\Request;

class BaseFieldRepository
{
    /**
     * Field instance.
     *
     * @var \Fjord\Crud\Field
     */
    protected $field;

    /**
     * Create new ListRepository instance.
     */
    public function __construct($controller, $config, $field, $model)
    {
        $this->controller = $controller;
        $this->config = $config;
        $this->field = $field;
        $this->model = $model;
    }

    /**
     * Update model.
     *
     * @param Request $request
     * @param mixed $model
     * @return void
     */
    public function update(Request $request, $model)
    {
        $model->update($request->all());
    }
}
