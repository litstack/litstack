<?php

namespace Fjord\Crud\Controllers\Concerns;

use Illuminate\Support\Str;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesCrudUpdateCreate
{
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
            /*
            if (!$field->canSave()) {
                unset($attributes[$field->local_key]);
                continue;
            }
            */
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
