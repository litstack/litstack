<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Fields\Block\Block;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Controllers\Api\Block\ManagesBlockMedia;
use Fjord\Crud\Controllers\Api\Block\ManagesBlockRelations;

trait CrudHasBlock
{
    use ManagesBlockMedia,
        ManagesBlockRelations;

    /**
     * Load single repeatable.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * @param string $form_name
     * @param string $field_id
     * @param string $block_id
     * @return CrudJs
     */
    public function loadRepeatable(CrudReadRequest $request, $identifier, $form_name, $field_id, $block_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $field = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $field instanceof Block ?: abort(404);

        $model = $this->findOrFail($identifier);

        return crud(
            $field->getRelationQuery($model)->findOrFail($block_id)
        );
    }

    /**
     * Load all repeatables.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * @param string $form_name
     * @param string $field_id
     * @return CrudJs
     */
    public function loadRepeatables(CrudReadRequest $request, $identifier, $form_name, $field_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $field = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $field instanceof Block ?: abort(404);

        $model = $this->findOrFail($identifier);

        return crud(
            $field->getResults($model)
        );
    }

    /**
     * Update form_block.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * @param string $form_name
     * @param string $field_id
     * @return CrudJs
     */
    public function storeRepeatable(CrudUpdateRequest $request, $identifier, $form_name, $field_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $field = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $field instanceof Block ?: abort(404);

        $field->hasRepeatable($request->type) ?: abort(404);

        $model = $this->findOrFail($identifier);

        $order_column = FormBlock::where([
            'type' => $request->type,
            'model_type' => $this->model,
            'model_id' => $model->id,
            'field_id' => $field->id
        ])->count();

        $block = new FormBlock();
        $block->type = $request->type;
        $block->model_type = $this->model;
        $block->model_id = $model->id;
        $block->field_id = $field->id;
        $block->order_column = $order_column;
        $block->save();

        return crud($block);
    }

    /**
     * Update form_block.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * @param string $form_name
     * @param string $field_id
     * @param integer $block_id
     * @return FormBlock
     */
    public function updateRepeatable(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $field = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $field instanceof Block ?: abort(404);

        $model = $this->findOrFail($identifier);

        $block = $model->{$field_id}()->findOrFail($block_id);

        // Validate request.
        $this->validate($request, $field->getRepeatable($block->type));

        $block->update($request->all());

        return $block;
    }

    /**
     * Update form_block.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * @param string $form_name
     * @param string $field_id
     * @param integer $block_id
     * @return integer
     */
    public function destroyRepeatable(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $field = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $field instanceof Block ?: abort(404);

        $model = $this->findOrFail($identifier);

        $block = $model->{$field_id}()->findOrFail($block_id);

        return $block->delete();
    }
}
