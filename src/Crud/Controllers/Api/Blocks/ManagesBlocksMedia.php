<?php

namespace Fjord\Crud\Controllers\Api\Blocks;

use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesBlocksMedia
{
    /**
     * Update media attributes
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param int $media_id
     * @return int
     */
    public function updateBlockMedia(CrudUpdateRequest $request, $id, $block_id, $media_id)
    {
        $model = $this->query()->findOrFail($id);
        $block = $model->blocks()->findOrFail($block_id);
        $media = $block->media()->findOrFail($media_id);
        $media->custom_properties = $request->custom_properties;
        return $media->save();
    }

    /**
     * Remove the specified blocks resource from storage.
     *
     * @param int $id
     * @param int $blockId
     * @param int $media_id
     * @return int
     */
    public function destroyBlockMedia(CrudUpdateRequest $request, $id, $block_id, $media_id)
    {
        $model = $this->query()->findOrFail($id);
        $block = $model->blocks()->findOrFail($block_id);
        if ($block->media()->findOrFail($media_id)->delete()) {
            return response()->json(['message' => __f('fj.image_deleted')], 200);
        }
    }

    /**
     * Order block media.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param int $blockId
     * @return void
     */
    public function orderBlockMedia(CrudUpdateRequest $request, $id, $blockId)
    {
        $ids = $request->ids ?? abort(404);
        $model = $this->query()->findOrFail($id);
        $block = $model->blocks()->findOrFail($blockId);
        $field = $this->config->form->findField($request->collection) ?? abort(404);
        $media = $block->media()->where('collection_name', $field->id)->get();

        return $this->order($media, $field, $ids);
    }
}
