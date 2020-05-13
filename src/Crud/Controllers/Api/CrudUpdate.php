<?php

namespace Fjord\Crud\Controllers\Api;

use Illuminate\Support\Str;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudUpdate
{
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
            /*
            if ($this->isRelationField($field)) {
                $this->updateRelated($model, $field, $attributes);
                continue;
            }
            */
            if (!array_key_exists($field->local_key, $attributes)) {
                continue;
            }
            if (!$field->canSave()) {
                unset($attributes[$field->local_key]);
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
