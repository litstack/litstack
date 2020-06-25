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
    public function __construct(Request $request)
    {
        $this->field = $this->getField;
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

[
    'payload' => [

    ],
    'action' => 'list',
    'type' => 'update'
]
