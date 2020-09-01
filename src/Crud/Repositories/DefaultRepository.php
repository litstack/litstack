<?php

namespace Ignite\Crud\Repositories;

use Ignite\Crud\CrudValidator;
use Ignite\Crud\Models\LitFormModel;
use Ignite\Crud\Requests\CrudCreateRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;

class DefaultRepository extends BaseFieldRepository
{
    /**
     * Load model.
     *
     * @param CrudReadRequest $request
     * @param mixed           $model
     *
     * @return CrudJs
     */
    public function load(CrudReadRequest $request, $model)
    {
        return crud($model);
    }

    /**
     * Update model.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     * @param object            $payload
     *
     * @return CrudJs
     */
    public function update(CrudUpdateRequest $request, $model, $payload)
    {
        CrudValidator::validate(
            (array) $payload,
            $this->form,
            CrudValidator::UPDATE
        );

        $this->fillAttributesToModel($model, (array) $payload);
        $attributes = $this->formatAttributes((array) $payload, $this->form->getRegisteredFields());

        $this->controller->fillOnUpdate($model);

        $model->update($attributes);

        return crud($model);
    }

    /**
     * Store new model.
     *
     * @param CrudCreateRequest $request
     * @param object            $payload
     *
     * @return CrudJs
     */
    public function store(CrudCreateRequest $request, $payload)
    {
        CrudValidator::validate(
            (array) $payload,
            $this->form,
            CrudValidator::CREATION
        );

        $attributes = $this->formatAttributes((array) $payload, $this->form->getRegisteredFields());

        if ($this->config->sortable) {
            $attributes[$this->config->orderColumn] = $this->controller->query()->count() + 1;
        }

        $model = $this->controller->getModel();
        $model = new $model($attributes);

        $this->fillAttributesToModel($model, (array) $payload);
        $this->controller->fillOnStore($model);

        $model->save();

        if ($model instanceof LitFormModel) {
            $model->update($attributes);
        }

        return crud($model);
    }
}
