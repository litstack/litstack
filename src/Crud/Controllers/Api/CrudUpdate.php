<?php

namespace Fjord\Crud\Controllers\Api;

use Illuminate\Support\Str;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudUpdate
{
    /**
     * Update model.
     *
     * @param CrudUpdateRequest $request
     * @param mixed $model
     * @return void
     */
    protected function updateModel(CrudUpdateRequest $request, $model)
    {
        $attributes = $request->all();

        foreach ($this->fields() as $field) {
            if ($this->isRelationField($field)) {
                $this->updateRelated($model, $field, $attributes);
            }
        }

        $model->update($attributes);
    }

    /**
     * Update related.
     *
     * @param mixed $model
     * @param Field $field
     * @param array $attributes
     * @return void
     */
    protected function updateRelated($model, $field, $attributes)
    {
        if (!array_key_exists($field->local_key, $attributes)) {
            return;
        }

        [$related, $key] = explode('.', $field->local_key);
        $value = $attributes[$field->local_key];

        $model->{$related}->update([$key => $value]);
    }

    protected function isRelationField($field)
    {
        return Str::contains($field->local_key, '.');
    }
}
