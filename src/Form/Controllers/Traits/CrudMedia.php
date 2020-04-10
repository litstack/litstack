<?php

namespace Fjord\Form\Controllers\Traits;

use Spatie\MediaLibrary\Models\Media;
use Fjord\Form\Requests\CrudUpdateRequest;

trait CrudMedia
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     */
    public function storeMedia(CrudUpdateRequest $request, $id)
    {
        $this->model::findOrFail($id);
        $model = $request->model::findOrFail($request->model_id);

        $formField = $model->findFormField($request->collection);

        $properties = [
            'title' => $request->title ?? null,
            'alt' => $request->alt ?? null,
        ];

        $customProperties = $formField->translatable ?? false
            ? [app()->getLocale() => $properties]
            : $properties;

        $model->addMedia($request->media)
            ->withCustomProperties($customProperties)
            ->toMediaCollection($request->collection);

        return response()->json('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyMedia(CrudUpdateRequest $request, $id, $media_id)
    {
        $this->model::findOrFail($id);

        if (Media::findOrFail($media_id)->delete()) {
            return 'success';
        }
    }

    public function updateMedia(CrudUpdateRequest $request, $id, $media_id)
    {
        $this->model::findOrFail($id);

        $media = Media::findOrFail($media_id);
        $media->custom_properties = $request->custom_properties;
        $media->save();

        return;
    }
}
