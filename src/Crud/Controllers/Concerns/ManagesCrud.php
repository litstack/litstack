<?php

namespace Fjord\Crud\Controllers\Concerns;

use Fjord\Crud\BaseForm;
use Fjord\Crud\CrudValidator;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesCrud
{
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
     * Find or faild model by identifier.
     *
     * @param string|integer $id
     * @return void
     */
    public function findOrFail($id)
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Validate request.
     *
     * @param CrudUpdateRequest|CrudCreateRequest $request
     * @param BaseForm $form
     * @param string|null $type
     * @return void
     */
    public function validate($request, BaseForm $form, $type = null)
    {
        CrudValidator::validate($request, $form, $type);
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
