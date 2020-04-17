<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait HasBlocks
{
    /**
     * Store new block.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param int $block_id
     * @return void
     */
    public function storeBlock(CrudUpdateRequest $request, $id, $field_id)
    {
        $model = $this->query()->findOrFail($id);
        $field = $model->findField($field_id);

        if (!$field instanceof Blocks) {
            abort(404);
        }

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

        return $block->eloquentJs();
    }

    /**
     * Update form_block.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function updateBlock(CrudUpdateRequest $request, $id, $field_id, $block_id)
    {
        $model = $this->query()->findOrFail($id);
        $field = $model->findField($field_id);

        if (!$field instanceof Blocks) {
            abort(404);
        }

        $block = $model->{$field_id}()->findOrFail($block_id);

        $block->update($request->all());

        return $block;
    }

    /**
     * Destroy form_block.
     *
     * @param Request $request
     * @param int $id
     * @param int $block_id
     * @return void
     */
    public function destroyBlock(CrudUpdateRequest $request, $id, $field_id, $block_id)
    {
        $model = $this->query()->findOrFail($id);
        $field = $model->findField($field_id);

        if (!$field instanceof Blocks) {
            abort(404);
        }

        $block = $model->{$field_id}()->findOrFail($block_id);

        return $block->delete();
    }
}
