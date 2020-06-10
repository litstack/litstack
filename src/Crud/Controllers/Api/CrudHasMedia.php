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
        $request->collection ?: abort(404);
        $request->media !== null ?: abort(404);

        $this->formExists($formName) ?: abort(404);

        $field = $this->getForm($formName)->findField($request->collection)
            ?? abort(404);

        $model = $this->model::findOrFail($identifier);

        return $this->storeMediaToModel($request, $model, $field);

        $this->edited($model, 'media:uploaded');
    }

    /**
     * Store media to model.
     *
     * @param CrudUpdateRequest $request
     * @param MediaField $field
     * @param int $model
     * @return void
     */
    public function storeMediaToModel($request, $model, $field)
    {
        if (!$field instanceof MediaField) {
            abort(404);
        }

        $request->validate([
            'media' => 'required|max:' . $field->fileSize * 1000
        ], __f('validation'), [
            'media' => $field->title
        ]);

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
     * @param string|integer $id
     * @param int $media_id
     * @return int
     */
    public function updateMedia(CrudUpdateRequest $request, $id, $form_name, $media_id)
    {
        $this->formExists($form_name) ?: abort(404);

        $model = $this->findOrFail($id);
        $media = $model->media()->findOrFail($media_id);
        $media->custom_properties = $request->custom_properties;

        $this->edited($model, 'media:updated');

        return $media->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * @param string $form_name
     * @return Response
     */
    public function destroyMedia(CrudUpdateRequest $request, $id, $form_name, $media_id)
    {
        $this->formExists($form_name) ?: abort(404);

        $model = $this->findOrFail($id);

        if ($model->media()->findOrFail($media_id)->delete()) {

            $this->edited($model, 'media:deleted');

            return response()->json(['message' => __f('fj.image_deleted')], 200);
        }
    }

    /**
     * Order media.
     *
     * @param CrudUpdateRequest $request
     * @param string|integer $id
     * @param string $form_name
     * @return void
     */
    public function orderMedia(CrudUpdateRequest $request, $id, $form_name)
    {
        $ids = $request->ids ?? abort(404);
        $this->formExists($form_name) ?: abort(404);
        $mediaField = $this->config->form->findField($request->collection) ?? abort(404);

        $model = $this->findOrFail($id);

        $query = $model->media()->where('collection_name', $mediaField->id);

        if (!$mediaField->sortable) {
            abort(404);
        }

        $response = $this->orderField($query, $mediaField, $ids);

        $this->edited($model, 'media:ordered');

        return $response;
    }
}
