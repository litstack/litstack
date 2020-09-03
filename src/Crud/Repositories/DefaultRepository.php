<?php

namespace Ignite\Crud\Repositories;

use Ignite\Crud\CrudValidator;
use Ignite\Crud\Field;
use Ignite\Crud\Fields\Relations\LaravelRelationField;
use Ignite\Crud\Models\LitFormModel;
use Ignite\Crud\Requests\CrudCreateRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Http\Request;

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

    /**
     * Get child field for relation fields.
     *
     * @param  Request $request
     * @param  string  $field_id
     * @return Field
     */
    public function getField(Request $request, $field_id)
    {
        if (! $this->field instanceof LaravelRelationField) {
            abort(404);
        }

        return $this->field->form->findField($field_id)
            ?: abort(404, debug("Coulnd't find field [$field_id]"));
    }

    /**
     * Get relation model.
     *
     * @param  Request    $request
     * @param  mixed      $model
     * @return Repeatable
     */
    public function getModel(Request $request, $model, $childRepository)
    {
        if (! $this->field instanceof LaravelRelationField) {
            abort(404);
        }

        return $this->field->getRelationQuery($model)
            ->where('id', $request->relation_id)
            ->firstOrFail();
    }
}
