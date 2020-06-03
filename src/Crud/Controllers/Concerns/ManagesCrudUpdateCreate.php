<?php

namespace Fjord\Crud\Controllers\Concerns;

use Fjord\Crud\CrudValidator;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesCrudUpdateCreate
{
    /**
     * Validate request.
     *
     * @param CrudUpdateRequest|CrudCreateRequest $request
     * @param BaseForm $form
     * @return void
     */
    public function validate($request, $form)
    {
        CrudValidator::validate($request, $form);
    }

    /**
     * Delete by query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function delete(Builder $query)
    {
        $query->delete();
    }

    /**
     * Update Crud model.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * * @param string $formName
     * @return mixed $model
     */
    public function update(CrudUpdateRequest $request, $identifier, $formName)
    {
        $this->formExists($formName) ?: abort(404);

        $model = $this->findOrFail($identifier);

        $this->validate($request, $this->config->form);

        $this->fillModelAttributes($model, $request, $this->fields());
        $attributes = $this->filterRequestAttributes($request, $this->fields());

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
    public function store(CrudCreateRequest $request)
    {
        $this->validate($request, $this->config->form);

        $attributes = $this->filterRequestAttributes($request, $this->fields());

        if ($this->config->sortable) {
            $attributes[$this->config->orderColumn] = $this->query()->count() + 1;
        }

        $model = $this->model::create($attributes);

        return crud($model);
    }

    /**
     * Fill model attributes.
     *
     * @return void
     */
    public function fillModelAttributes($model, $request, $fields)
    {
        foreach ($fields as $field) {
            if (!$request->has($field->local_key)) {
                continue;
            }

            $field->fillModel($model, $field->local_key, $request->{$field->local_key});
        }
    }

    /**
     * Filter request attributes.
     *
     * @param CrudUpdateRequest|CrudCreateRequest $request
     * @param Collection $fields
     * @return array
     */
    public function filterRequestAttributes($request, $fields)
    {
        $attributes = $request->all();

        foreach ($fields as $field) {
            if (!array_key_exists($field->local_key, $attributes)) {
                continue;
            }
            // Format value before update.
            if (method_exists($field, 'format')) {
                $attributes[$field->local_key] = $field->format(
                    $attributes[$field->local_key]
                );
            }
        }

        return $attributes;
    }
}
