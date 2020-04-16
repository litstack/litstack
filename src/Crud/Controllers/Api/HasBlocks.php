<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Form\Requests\CrudUpdateRequest;

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
        $model = $this->model::findOrFail($id);

        $field = $model->findField($field_id);
        if (!$field instanceof Blocks) {
            abort(404);
        }

        $block = new FormBlock();
        $block->type = $request->type;
        $block->model_type = $this->model;
        $block->model_id = $model->id;
        $block->field_id = $field->id;
        $block->value = $request->value;
        $block->order_column = $request->order_column;
        $block->save();

        return $block->eloquentJs();
    }
}
