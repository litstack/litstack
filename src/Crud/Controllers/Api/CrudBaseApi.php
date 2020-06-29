<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Api\CrudApi;
use Illuminate\Http\Request;
use Fjord\Crud\Actions\ActionResolver;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudBaseApi
{
    /**
     * Perform crud api call.
     *
     * @param Request $request
     * @param ActionResolver $resolver
     * @return mixed
     */
    public function api(Request $request, CrudApi $api)
    {
        return $api->handle($request, $this);
    }

    /**
     * Update Crud model.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * * @param string $form_name
     * @return mixed $model
     */
    public function update(CrudUpdateRequest $request, $id, $form_name)
    {
        $form = $this->getForm($form_name) ?: abort(404);
        $model = $this->findOrFail($id);
        $this->validate($request, $this->getForm($form_name));

        $this->fillModelAttributes($model, $request, $form->getRegisteredFields());
        $attributes = $this->filterRequestAttributes($request, $form->getRegisteredFields());

        $this->fillOnUpdate($model);

        $model->update($attributes);

        if ($model->last_edit) {
            $model->load('last_edit');
        }

        return crud($model);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Fjord\Crud\Requests\CrudCreateRequest  $request
     * @return mixed
     */
    public function store(CrudCreateRequest $request, $form_name)
    {
        $form = $this->getForm($form_name) ?: abort(404);
        $this->validate($request, $form);

        $attributes = $this->filterRequestAttributes($request, $form->getRegisteredFields());

        if ($this->config->sortable) {
            $attributes[$this->config->orderColumn] = $this->query()->count() + 1;
        }

        $model = new $this->model($attributes);

        $this->fillOnStore($this->model);

        $model->save();

        return crud($model);
    }

    /**
     * Fill model on store.
     *
     * @param mixed $model
     * @return void
     */
    public function fillOnStore($model)
    {
        //
    }

    /**
     * Fill model on update.
     *
     * @param mixed $model
     * @return void
     */
    public function fillOnUpdate($model)
    {
        //
    }
}
