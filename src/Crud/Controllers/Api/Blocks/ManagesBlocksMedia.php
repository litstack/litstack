<?php

namespace Fjord\Crud\Controllers\Api\Blocks;

use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesBlocksMedia
{
    /**
     * Store a newly created block resource in storage.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $field_id
     * @param int $block_id
     * @return response
     */
    public function storeBlockMedia(CrudUpdateRequest $request, $id, $field_id, $block_id)
    {
        $model = $this->model::findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $field = $block->findField($request->collection) ?? abort(404);

        return $this->storeMediaToModel($request, $block, $field);
    }

    /**
     * Update media attributes
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $field_id
     * @param int $block_id
     * @param int $media_id
     * @return int
     */
    public function updateBlockMedia(CrudUpdateRequest $request, $id, $field_id, $block_id, $media_id)
    {
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $media = $block->media()->findOrFail($media_id);
        $media->custom_properties = $request->custom_properties;
        return $media->save();
    }

    /**
     * Remove the specified blocks resource from storage.
     *
     * @param int $id
     * @param string $field_id
     * @param int $block_id
     * @param int $media_id
     * @return int
     */
    public function destroyBlockMedia(CrudUpdateRequest $request, $id, $field_id, $block_id, $media_id)
    {
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        if ($block->media()->findOrFail($media_id)->delete()) {
            return response()->json(['message' => __f('fj.image_deleted')], 200);
        }
    }

    /**
     * Order block media.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param string $field_id
     * @param int $block_id
     * @return void
     */
    public function orderBlockMedia(CrudUpdateRequest $request, $id, $field_id, $block_id)
    {
        $ids = $request->ids ?? abort(404);
        $model = $this->query()->findOrFail($id);
        $blockField = $this->config->form->findField($field_id) ?? abort(404);
        $block = $blockField->relation($model, $query = true)->findOrFail($block_id);
        $field = $block->findField($request->collection) ?? abort(404);
        $query = $block->media()->where('collection_name', $field->id);

        if (!$field->sortable) {
            abort(404);
        }

        return $this->orderField($query, $field, $ids);
    }
}
