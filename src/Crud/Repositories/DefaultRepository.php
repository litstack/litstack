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

        [$attributes, $pivot] = $this->filterPivotAttributes((array) $payload);

        if (! empty($pivot)) {
            $this->updateExistingPivot($model, $pivot);
        }

        $this->fillAttributesToModel($model, $attributes);
        $attributes = $this->formatAttributes($attributes, $this->form->getRegisteredFields());

        $this->controller->fillOnUpdate($model);

        $model->update($attributes);

        return crud($model);
    }

    /**
     * Update pivot fields.
     *
     * @param  Model $related
     * @param  array $attributes
     * @return void
     */
    protected function updateExistingPivot($related, $attributes)
    {
        $model = $this->field->getParentForm()->getModel()::findOrFail(
            request()->route('id')
        );

        $relation = $this->field->local_key;

        $model->$relation()->updateExistingPivot(
            $related->id, $attributes
        );
    }

    /**
     * Filter pivot attributes.
     *
     * @param  array $attributes
     * @return void
     */
    protected function filterPivotAttributes($attributes)
    {
        $pivot = [];

        foreach ($this->form->getRegisteredFields() as $field) {
            if (! $field->is_pivot) {
                continue;
            }

            if (! array_key_exists($field->id, $attributes)) {
                continue;
            }

            $pivot[$field->id] = $attributes[$field->id];
            unset($attributes[$field->id]);
        }

        return [$attributes, $pivot];
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
            $attributes[$this->config->orderColumn] = $this->controller->getQuery()->count() + 1;
        }

        $model = $this->controller->getModel();
        $model = $this->controller->initialQuery()->make($attributes);

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
        return $this->field;
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
