<?php

namespace Ignite\Crud\Repositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Crud\Fields\Media\MediaField;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Conversions\FileManipulator;
use Spatie\MediaLibrary\Support\ImageFactory;

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
     *
     * @param  ConfigHandler      $config
     * @param  CrudBaseController $controller
     * @param  BaseForm           $form
     * @param  MediaField         $field
     * @return void
     */
    public function __construct(ConfigHandler $config, $controller, $form, MediaField $field)
    {
        parent::__construct($config, $controller, $form, $field);
    }

    /**
     * Update media custom_properties.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @param  object            $payload
     * @return void
     */
    public function update(CrudUpdateRequest $request, $model, $payload)
    {
        $customProperties = $payload->custom_properties ?? abort(404, debug('Missing payload [custom_properties].'));
        $media = $model->media()->findOrFail($request->media_id);
        $media->custom_properties = $customProperties;
        $media->save();

        if ($media->is_cropped) {
            app(FileManipulator::class)->createDerivedFiles(
                $model->media()->findOrFail($request->media_id)
            );
        }
    }

    /**
     * Store new media in model.
     *
     * @param  CrudReadRequest $request
     * @param  mixed           $model
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
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
     * @return void
     */
    public function destroy(CrudUpdateRequest $request, $model)
    {
        if ($model->media()->findOrFail($request->media_id)->delete()) {
            return response()->success(
                ucfirst(__lit('base.item_deleted', ['item' => __lit('base.image')]))
            );
        }
    }

    /**
     * Order media.
     *
     * @param  CrudUpdateRequest $request
     * @param  mixed             $model
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
        ], __lit('validation'), [
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

        if ($crop = $this->getCropData($request)) {
            $customProperties['crop'] = $crop;
        }

        $image = ImageFactory::load($request->media->path());

        if (Str::startsWith($request->media->getClientMimeType(), 'image')) {
            $customProperties['original_dimensions'] = [
                'width'  => $image->getWidth(),
                'height' => $image->getHeight(),
            ];
        }

        $media = $model->addMedia($request->media)
            ->preservingOriginal()
            ->withCustomProperties($customProperties)
            ->toMediaCollection($request->collection);

        $media->showOrignial = true;

        return response()->json($media, 200);
    }

    /**
     * Get crop data from request.
     *
     * @param  Request     $request
     * @return void|object
     */
    protected function getCropData(Request $request)
    {
        if (! $crop = $request->crop) {
            return;
        }

        return json_decode($crop);
    }

    /**
     * Destroy previous media if there are to many.
     *
     * @param  mixed $model
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
