<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Controllers\Api\Blocks\ManagesBlocksMedia;
use Fjord\Crud\Controllers\Api\Blocks\ManagesBlocksRelations;

trait CrudHasBlocks
{
    use ManagesBlocksMedia,
        ManagesBlocksRelations;

    /**
     * Load blocks relation index.
     *
     * @param CrudReadRequest $request
     * @param int $id
     * @param string $relation
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function blockRelationIndex(CrudReadRequest $request, $id, $field_id, $block_id, $relation)
    {
        $model = $this->query()->findOrFail($id);

        $blockField = $this->config->form->findField($field_id) ?? abort(404);

        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);

        $field = $block->findField($relation);

        if (!$field->isRelation() || $field instanceof Blocks) {
            abort(404);
        }

        return $field->getQuery()->get();
    }

    /**
     * Fetch all blocks.
     *
     * @param CrudReadRequest $request
     * @param int $id
     * @param int $field_id
     * @return void
     */
    public function blockIndex(CrudReadRequest $request, $id, $field_id)
    {
        $model = $this->query()->findOrFail($id);
        $field = $this->config->form->findField($field_id);

        if (!$field instanceof Blocks) {
            abort(404);
        }

        return crud(
            $field->relation($model)
        );
    }

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
        $field = $this->config->form->findField($field_id) ?? abort(404);

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

        return crud($block);
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
        $field = $this->config->form->findField($field_id);

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
        $field = $this->config->form->findField($field_id);

        if (!$field instanceof Blocks) {
            abort(404);
        }

        $block = $model->{$field_id}()->findOrFail($block_id);

        return $block->delete();
    }
}
