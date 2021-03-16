<?php

namespace Ignite\Crud\Repositories;

use Ignite\Crud\CrudValidator;
use Ignite\Crud\Field;
use Ignite\Crud\Fields\Relations\LaravelRelationField;
use Ignite\Crud\Models\LitFormModel;
use Ignite\Crud\Requests\CrudCreateRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;
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
        return crud($model, $this->config);
    }

    /**
     * Update model.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @param  object            $payload
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

        return crud($model, $this->config);
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
     * @return array
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
     * @param  CrudCreateRequest $request
     * @param  object            $payload
     * @param  Model             $model
     * @return CrudJs
     */
    public function store(CrudCreateRequest $request, $payload, $model = null)
    {
        CrudValidator::validate(
            (array) $payload,
            $this->form,
            CrudValidator::CREATION
        );

        $attributes = $this->formatAttributes((array) $payload, $this->form->getRegisteredFields());

        if ($this->configMatchesModel($model) && $this->config->sortable) {
            $attributes[$this->config->orderColumn] = $this->controller->getQuery()->count() + 1;
        }

        if (is_null($model)) {
            $model = $this->controller->initialQuery()->make($attributes);
        } else {
            $model->fill($attributes);
        }

        $this->fillAttributesToModel($model, (array) $payload);

        if ($this->configMatchesModel($model)) {
            $this->controller->fillOnStore($model);
        }

        $model->save();

        if ($model instanceof LitFormModel) {
            $model->update($attributes);
        }

        if ($this->config->has('show')) {
            $this->config->show->fireEvent('created', $model);
        }

        return crud($model, $this->config);
    }

    /**
     * Determines if the current config matches the model. This will not be true
     * when a relationship model is handled.
     *
     * @param  mixed $model
     * @return bool
     */
    protected function configMatchesModel($model)
    {
        if (is_null($model)) {
            return true;
        }

        return get_class($model) == $this->config->model;
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
     * @param  Request $request
     * @param  mixed   $model
     * @return Model
     */
    public function getModel(Request $request, $model, $childRepository)
    {
        if (! $this->field instanceof LaravelRelationField) {
            abort(404);
        }

        if (is_null($request->relation_id)) {
            if ($this->config->has('show')) {
                $this->config->show->on('created', function ($related) use ($model) {
                    $repository = $this->field->getRepository();
                    $repository = new $repository($this->config, $this->controller, $this->form, $this->field);

                    $repository->link($model, $related);
                });
            }

            return $this->field->getRelationQuery($model)->make();
        }

        $query = $this->field->getRelationQuery($model);

        return $query
            ->where($query->qualifyColumn('id'), $request->relation_id)
            ->firstOrFail();
    }
}
