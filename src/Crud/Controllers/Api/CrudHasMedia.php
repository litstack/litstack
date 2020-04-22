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
     */
    public function storeMedia(CrudUpdateRequest $request, $id)
    {
        $model = $this->query()->findOrFail($id);

        $field = $this->config->form->findField($request->collection)
            ?? abort(404);

        $this->destroyPreviousMedia($model, $field);

        $properties = [
            'title' => $request->title ?? null,
            'alt' => $request->alt ?? null,
        ];

        $customProperties = $field->translatable ?? false
            ? [app()->getLocale() => $properties]
            : $properties;

        $model->addMedia($request->media)
            ->withCustomProperties($customProperties)
            ->toMediaCollection($request->collection);

        return response()->json('success', 200);
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
        return $model->media()->findOrFail($media_id)->delete();
    }
}
