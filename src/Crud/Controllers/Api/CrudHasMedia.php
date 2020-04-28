<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\MediaField;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait CrudHasMedia
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @return response
     */
    public function storeMedia(CrudUpdateRequest $request, $id)
    {
        $model = $this->model::findOrFail($id);

        $field = $this->config->form->findField($request->collection)
            ?? abort(404);

        return $this->storeMediaToModel($request, $model, $field);
    }

    /**
     * Store a newly created block resource in storage.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param int $blockId
     * @return response
     */
    public function storeBlockMedia(CrudUpdateRequest $request, $id, $blockId)
    {
        $model = $this->model::findOrFail($id);

        $block = $model->blocks()->findOrFail($blockId);

        $field = $block->findField($request->collection) ?? abort(404);

        return $this->storeMediaToModel($request, $block, $field);
    }

    /**
     * Store media to model.
     *
     * @param CrudUpdateRequest $request
     * @param Field $field
     * @param int $model
     * @return void
     */
    public function storeMediaToModel($request, $model, $field)
    {
        $this->destroyPreviousMedia($model, $field);

        $properties = [
            'title' => $request->title ?? null,
            'alt' => $request->alt ?? null,
        ];

        $customProperties = $field->translatable ?? false
            ? [app()->getLocale() => $properties]
            : $properties;

        $media = $model->addMedia($request->media)
            ->withCustomProperties($customProperties)
            ->toMediaCollection($request->collection);

        return response()->json($media, 200);
    }

    /**
     * Destroy first media if there are to many.
     *
     * @param mixed $model
     * @param MediaField $field
     * @return void
     */
    public function destroyPreviousMedia($model, MediaField $field)
    {
        $mediaCount = $model->media()
            ->where('collection_name', $field->id)
            ->count();

        if (!$field->override) {
            if ($mediaCount >= $field->maxFiles) {
                abort(405);
            }
            return;
        }

        if ($mediaCount < $field->maxFiles) {
            return;
        }

        $media = $model->media()
            ->where('collection_name', $field->id)
            ->take($mediaCount - $field->maxFiles + 1)
            ->orderBy('id')
            ->get();

        foreach ($media as $m) {
            $m->delete();
        }
    }

    /**
     * Update media attributes
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @param int $media_id
     * @return int
     */
    public function updateMedia(CrudUpdateRequest $request, $id, $media_id)
    {
        $model = $this->query()->findOrFail($id);
        $media = $model->media()->findOrFail($media_id);
        $media->custom_properties = $request->custom_properties;
        return $media->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param int $media_id
     * @return int
     */
    public function destroyMedia(CrudUpdateRequest $request, $id, $media_id)
    {
        $model = $this->query()->findOrFail($id);
        if ($model->media()->findOrFail($media_id)->delete()) {
            return response()->json(['message' => __f('fj.image_deleted')], 200);
        }
    }

    /**
     * Order media.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     * @return void
     */
    public function orderMedia(CrudUpdateRequest $request, $id)
    {
        $model = $this->query()->findOrFail($id);
        $ids = $request->ids ?? abort(404);
        $field = $this->config->form->findField($request->collection) ?? abort(404);
        $media = $model->media()->where('collection_name', $field->id)->get();

        return $this->order($media, $field, $ids);
    }
}
