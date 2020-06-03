<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Crud\Fields\Media\MediaField;
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
    public function storeMedia(CrudUpdateRequest $request, $identifier, $formName)
    {
        $this->formExists($formName) ?: abort(404);

        $request->collection ?: abort(404);
        $request->media !== null ?: abort(404);

        $model = $this->model::findOrFail($identifier);

        $field = $this->config->form->findField($request->collection)
            ?? abort(404);

        return $this->storeMediaToModel($request, $model, $field);

        $this->edited($model, 'media:uploaded');
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
            ->preservingOriginal()
            ->withCustomProperties($customProperties)
            ->toMediaCollection($request->collection);

        $this->edited($model, 'media:uploaded');

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

        $this->edited($model, 'media:updated');

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

            $this->edited($model, 'media:deleted');

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
        $query = $model->media()->where('collection_name', $field->id);

        if (!$field->sortable) {
            abort(404);
        }

        $response = $this->orderField($query, $field, $ids);

        $this->edited($model, 'media:ordered');

        return $response;
    }
}
