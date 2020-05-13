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
        $this->validateUpdate($request);

        $attributes = $request->all();

        foreach ($this->fields() as $field) {
            if ($this->isRelationField($field)) {
                $this->updateRelated($model, $field, $attributes);
                continue;
            }
            if (!array_key_exists($field->local_key, $attributes)) {
                continue;
            }
            if (method_exists($field, 'format')) {
                $attributes[$field->local_key] = $field->format(
                    $attributes[$field->local_key]
                );
            }
        }

        $model->update($attributes);
    }

    public function validateUpdate(CrudUpdateRequest $request)
    {
        $rules = [];
        foreach ($this->fields() as $field) {
            if (!$field->rules) {
                continue;
            }
            if ($field->translatable) {
                foreach (config('translatable.locales') as $locale) {
                    $rules["{$locale}.{$field->local_key}"] = $field->rules;
                }
            } else {
                $rules[$field->local_key] = $field->rules;
            }
        }
        if (empty($rules)) {
            return;
        }

        $request->validate($rules);
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
