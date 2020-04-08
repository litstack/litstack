<?php

namespace Fjord\Form\Controllers;

use Fjord\Fjord\Controllers\FjordController;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends FjordController
{
    protected $model;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->setModel($request->model);

        $model = $this->model::findOrFail($request->id);

        $formField = $model->findFormField($request->collection);

        $properties = [
            'title' => $request->title ?? null,
            'alt' => $request->alt ?? null,
        ];
        $customProperties = $formField->translatable
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
    public function destroy($id)
    {
        if (Media::findOrFail($id)->delete()) {
            return 'success';
        }
    }

    public function attributes(Request $request)
    {
        $media = Media::findOrFail($request->id);
        $media->custom_properties = $request->custom_properties;
        $media->save();

        return;
    }

    protected function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
