<?php

namespace Fjord\Crud\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudHasModal
{
    /**
     * Update modal field.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $modal_id
     * @return CrudJs
     */
    public function updateModal(CrudUpdateRequest $request, $id, $modal_id)
    {
        $model = $this->query()->findOrFail($id);
        $modal = $this->config->form->findField($modal_id);

        $request->validate(
            $modal->form->getRules($request),
            __f('validation'),
            $modal->getRegisteredFields()->mapWithKeys(function ($field) {
                return [$field->local_key => $field->title];
            })->toArray()
        );

        $model->update(
            $this->filterRequestAttributes($request, $modal->getRegisteredFields())
        );

        if ($model->last_edit) {
            $model->load('last_edit');
        }

        return crud($model);
    }
}
