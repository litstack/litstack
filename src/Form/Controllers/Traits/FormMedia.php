<?php

namespace Fjord\Form\Controllers\Traits;

use Fjord\Form\Database\FormField;
use Spatie\MediaLibrary\Models\Media;
use Fjord\Crud\Requests\FormUpdateRequest;

trait FormMedia
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CrudUpdateRequest $request
     * @param int $id
     */
    public function storeMedia(FormUpdateRequest $request, $id)
    {
        FormField::findOrFail($id);
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
    public function destroyMedia(FormUpdateRequest $request, $id, $media_id)
    {
        FormField::findOrFail($id);

        if (Media::findOrFail($media_id)->delete()) {
            return 'success';
        }
    }

    public function updateMedia(FormUpdateRequest $request, $id, $media_id)
    {
        FormField::findOrFail($id);

        $media = Media::findOrFail($media_id);
        $media->custom_properties = $request->custom_properties;
        $media->save();

        return;
    }
}
