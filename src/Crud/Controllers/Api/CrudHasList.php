<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Fields\ListField;
use Fjord\Crud\Models\FormListItem;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudHasList
{
    public function loadListItem(CrudReadRequest $request, $id, $form_type, $field_id, $list_item_id)
    {
        $this->formExists($form_type) ?: abort(404);
        $field = $this->getForm($form_type)->findField($field_id) ?? abort(404);
        $field instanceof ListField ?: abort(404);

        $model = $this->findOrFail($id);

        return crud(
            $field->getRelationQuery($model)->findOrFail($list_item_id)
        );
    }

    public function loadListItems(CrudReadRequest $request, $id, $form_type, $field_id)
    {
        $this->formExists($form_type) ?: abort(404);
        $field = $this->getForm($form_type)->findField($field_id) ?? abort(404);
        $field instanceof ListField ?: abort(404);

        $model = $this->findOrFail($id);

        return crud(
            $field->getResults($model)
        );
    }

    public function storeListItem(CrudUpdateRequest $request, $id, $form_type, $field_id)
    {
        $this->formExists($form_type) ?: abort(404);
        $field = $this->getForm($form_type)->findField($field_id) ?? abort(404);
        $field instanceof ListField ?: abort(404);

        $model = $this->findOrFail($id);

        $parent_id = $request->parent_id ?: 0;
        $parent = $parent_id ? FormListItem::findOrFail($request->parent_id) : null;

        $order_column = FormListItem::where([
            'config_type' => $this->config->getType(),
            'form_type' => $form_type,
            'model_type' => $this->model,
            'model_id' => $model->id,
            'field_id' => $field->id,
            'parent_id' => $parent_id
        ])->count();

        $listItem = new FormListItem();
        $listItem->model_type = $this->model;
        $listItem->model_id = $model->id;
        $listItem->field_id = $field->id;
        $listItem->config_type = get_class($this->config->getConfig());
        $listItem->form_type = $form_type;
        $listItem->parent_id = $parent_id;
        $listItem->order_column = $order_column;
        $listItem->save();
    }

    public function destroyListItem(CrudUpdateRequest $request, $id, $form_type, $field_id, $list_item_id)
    {
        $this->formExists($form_type) ?: abort(404);
        $field = $this->getForm($form_type)->findField($field_id) ?? abort(404);
        $field instanceof ListField ?: abort(404);

        $model = $this->findOrFail($id);

        $block = $model->{$field_id}()->findOrFail($list_item_id);

        return $block->delete();
    }

    public function updateListItem(CrudUpdateRequest $request, $id, $form_type, $field_id, $list_item_id)
    {
        $this->formExists($form_type) ?: abort(404);
        $field = $this->getForm($form_type)->findField($field_id) ?? abort(404);
        $field instanceof ListField ?: abort(404);

        $model = $this->findOrFail($id);

        $listItem = $model->{$field_id}()->findOrFail($list_item_id);

        // Validate request.
        $this->validate($request, $field->form);

        $listItem->update($request->all());

        return $listItem;
    }
}
