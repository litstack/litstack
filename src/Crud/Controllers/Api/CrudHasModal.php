<?php

namespace Fjord\Crud\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudHasModal
{
    public function updateModal(CrudUpdateRequest $request, $id, $modal_id)
    {
        $model = $this->query()->findOrFail($id);
        $modal = $this->config->form->findField($modal_id);

        $request->validate(
            $modal->form->getRules($request)
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
