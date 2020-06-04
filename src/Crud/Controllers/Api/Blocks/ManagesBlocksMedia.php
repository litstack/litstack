<?php

namespace Fjord\Crud\Controllers\Api\Blocks;

use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesBlocksMedia
{
    /**
     * Store a newly created block resource in storage.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $field_id
     * @param int $block_id
     * @return response
     */
    public function storeBlockMedia(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $blockField instanceof Blocks ?: abort(404);
        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);

        $mediaField = $block->findField($request->collection) ?? abort(404);

        return $this->storeMediaToModel($request, $block, $mediaField);
    }

    /**
     * Update media attributes
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $field_id
     * @param int $block_id
     * @param int $media_id
     * @return int
     */
    public function updateBlockMedia(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id, $media_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $blockField instanceof Blocks ?: abort(404);

        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block model.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Find media model.
        $media = $block->media()->findOrFail($media_id);
        $media->custom_properties = $request->custom_properties;
        return $media->save();
    }

    /**
     * Remove the specified blocks resource from storage.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $field_id
     * @param int $block_id
     * @param int $media_id
     * @return int
     */
    public function destroyBlockMedia(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id, $media_id)
    {
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $blockField instanceof Blocks ?: abort(404);
        // Find model.
        $model = $this->query()->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Delete media.
        if ($block->media()->findOrFail($media_id)->delete()) {
            return response()->json(['message' => __f('fj.image_deleted')], 200);
        }
    }

    /**
     * Order block media.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $identifier
     * @param string $form_name
     * @param string $field_id
     * @param int $block_id
     * @return void
     */
    public function orderBlockMedia(CrudUpdateRequest $request, $identifier, $form_name, $field_id, $block_id)
    {
        $ids = $request->ids ?? abort(404);
        $this->formExists($form_name) ?: abort(404);
        $blockField = $this->getForm($form_name)->findField($field_id) ?? abort(404);
        $blockField instanceof Blocks ?: abort(404);
        // Find model.
        $model = $this->findOrFail($identifier);
        // Find block.
        $block = $blockField->getRelationQuery($model)->findOrFail($block_id);
        // Find media.
        $mediaFiel = $block->findField($request->collection) ?? abort(404);
        $query = $block->media()->where('collection_name', $mediaFiel->id);

        if (!$mediaFiel->sortable) {
            abort(404);
        }

        return $this->orderField($query, $mediaFiel, $ids);
    }
}
