<?php

namespace Fjord\Crud\Repositories;

use Fjord\Crud\Fields\Media\MediaField;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

class MediaRepository extends BaseFieldRepository
{
    /**
     * MediaField field instance.
     *
     * @var MediaField
     */
    protected $field;

    /**
     * Create new MediaRepository instance.
     */
    public function __construct($config, $controller, $form, MediaField $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Update media custom_properties.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     * @param object            $payload
     *
     * @return void
     */
    public function update(CrudUpdateRequest $request, $model, $payload)
    {
        $customProperties = $payload->custom_properties ?? abort(404, debug('Missing payload [custom_properties].'));
        $media = $model->media()->findOrFail($request->media_id);
        $media->custom_properties = $customProperties;
        $media->save();
    }

    /**
     * Store new media in model.
     *
     * @param CrudReadRequest $request
     * @param mixed           $model
     *
     * @return void
     */
    public function store(CrudUpdateRequest $request, $model)
    {
        $request->collection ?: abort(404);
        $request->media !== null ?: abort(404);

        return $this->storeMediaToModel($request, $model);
    }

    /**
     * Destroy media from model.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        if ($model->media()->findOrFail($request->media_id)->delete()) {
            return response()->json(['message' => __f('fj.image_deleted')], 200);
        }
    }

    /**
     * Order media.
     *
     * @param CrudUpdateRequest $request
     * @param mixed             $model
     *
     * @return void
     */
    public function order(CrudUpdateRequest $request, $model)
    {
        if (! $this->field->sortable) {
            abort(404, debug("The media field [{$this->field->id}] is not sortable."));
        }

        $ids = $request->ids ?? abort(404);
        $query = $model->media()->where('collection_name', $this->field->id);

        $this->orderField($query, $this->field, $ids);
    }

    /**
     * Store media to model.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return Response
     */
    protected function storeMediaToModel($request, $model)
    {
        $request->validate([
            'media' => 'required|max:'.$this->field->maxFileSize * 1000,
        ], __f('validation'), [
            'media' => $this->field->title,
        ]);

        $this->destroyPreviousMedia($model);

        $properties = [
            'title' => $request->title ?? null,
            'alt'   => $request->alt ?? null,
        ];

        $customProperties = $this->field->translatable ?? false
            ? [app()->getLocale() => $properties]
            : $properties;

        $media = $model->addMedia($request->media)
            ->preservingOriginal()
            ->withCustomProperties($customProperties)
            ->toMediaCollection($request->collection);

        $media->url = $media->getUrl();

        return response()->json($media, 200);
    }

    /**
     * Destroy previous media if there are to many.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function destroyPreviousMedia($model)
    {
        $mediaCount = $model->media()
            ->where('collection_name', $this->field->id)
            ->count();

        if (! $this->field->override) {
            if ($mediaCount >= $this->field->maxFiles) {
                abort(405, 'Max files count reached.');
            }

            return;
        }

        if ($mediaCount < $this->field->maxFiles) {
            return;
        }

        $media = $model->media()
            ->where('collection_name', $this->field->id)
            ->take($mediaCount - $this->field->maxFiles + 1)
            ->orderBy('id')
            ->get();

        foreach ($media as $m) {
            $m->delete();
        }
    }
}
