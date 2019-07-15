<?php

namespace AwStudio\Fjord\Http\Controllers;

use AwStudio\Fjord\Http\Controllers\FjordController;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class FjordMediaController extends FjordController
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

        $model->addMedia($request->media)
              ->withCustomProperties([
                  'title' => $request->title ?? null,
                  'alt' => $request->alt ?? null,
              ])
              ->toMediaCollection($request->collection);

        return $model->getMedia($request->collection);
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
        foreach ($request->media_attributes as $media) {

            $row = Media::findOrFail($media['id']);
            foreach ($media['custom_properties'] as $key => $value) {
                $row->setCustomProperty($key, $value);
            }
            $row->save();

        }
        return 'success';
    }

    protected function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
