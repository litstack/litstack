<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudHasModal
{
    /**
     * Update modal field.
     *
     * @param CrudUpdateRequest $request
     * @param string $form_name
     * @param string $field_id
     * @param string $modal_id
     * @return CrudJs
     */
    public function updateModal(CrudUpdateRequest $request, $identifier, $form_name, $modal_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $modelField = $this->getForm($form_name)->findField($modal_id) ?? abort(404);
        $model = $this->findOrFail($identifier);

        $this->validate($request, $modelField->form);

        $model->update(
            $this->filterRequestAttributes($request, $modelField->getRegisteredFields())
        );

        if ($model->last_edit) {
            $model->load('last_edit');
        }

        return crud($model);
    }
}
