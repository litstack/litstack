<?php

namespace Fjord\Crud\Controllers;

use Fjord\Crud\RelationField;
use Fjord\User\Models\FjordUser;
use Fjord\Crud\Fields\Media\MediaField;
use Fjord\Crud\Requests\CrudReadRequest;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudDeleteRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;

abstract class CrudController
{
    use Api\CrudBaseApi,
        Api\CrudHasIndex,
        Api\CrudHasRelations,
        Api\CrudHasBlocks,
        Api\CrudHasMedia,
        Api\CrudHasOrder,
        Api\CrudHasModal,
        Concerns\ManagesForm,
        Concerns\ManagesConfig,
        Concerns\ManagesCrud;

    /**
     * The Model Class e.g. App\Models\Post
     *
     * @var string
     */
    protected $model;

    /**
     * Authorize request for operation.
     *
     * @param \Fjord\User\Models\FjordUser $user
     * @param string $operation
     * @return boolean
     */
    abstract public function authorize(FjordUser $user, string $operation): bool;

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query(): Builder;

    /**
     * Create new CrudController instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->config = $this->loadConfig();
    }

    /**
     * Load model.
     *
     * @param CrudReadRequest $request
     * @param int $id
     * @return array
     */
    public function load(CrudReadRequest $request, $id)
    {
        $model = $this->query()->findOrFail($id);
        $model->last_edit;

        return crud(
            $model
        );
    }

    /**
     * Delete by query.
     *
     * @param Builder $query
     * @return void
     */
    public function delete(Builder $query)
    {
        $query->delete();
    }

    /**
     * Delete one.
     *
     * @param CrudDeleteRequest $request
     * @return void
     */
    public function destroy(CrudDeleteRequest $request, $id)
    {
        $this->delete(
            $this->query()->where('id', $id)
        );
    }

    /**
     * Delete all.
     *
     * @param CrudDeleteRequest $request
     * @return void
     */
    public function destroyAll(CrudDeleteRequest $request)
    {
        if (!is_array($request->ids)) {
            abort(405);
        }

        $this->delete($this->query()->whereIn('id', $request->ids));

        return response()->json([

            'message' => __f('messages.deleted_items', ['count' => count($request->ids)])
        ], 200);
    }

    /**
     * Show Crud index.
     *
     * @param CrudReadRequest $request
     * @return View
     */
    public function index(CrudReadRequest $request)
    {
        $config = $this->config->get(
            'index',
            'route_prefix',
            'names',
            'sortBy',
            'sortByDefault',
            'perPage',
            'filter',
            'expandIndexContainer',
            'sortable',
            'orderColumn'
        );
        $config['expand'] = $config['expandIndexContainer'];

        return view('fjord::app')
            ->withTitle($config['names']['plural'])
            ->withComponent($this->config->indexComponent)
            ->withProps([
                'config' => $config,
                'headerComponents' => [],
            ]);
    }

    /**
     * Show Crud create.
     *
     * @param CrudCreateRequest $request
     * @return void
     */
    public function create(CrudCreateRequest $request)
    {
        $config = $this->config->get(
            'form',
            'names',
            'permissions',
            'route_prefix'
        );

        return view('fjord::app')
            ->withComponent($this->config->formComponent)
            ->withTitle(__f('base.item_create', [
                'item' => $config['names']['singular']
            ]))
            ->withProps([
                'crud-model' => crud(new $this->model),
                'config' => $config,
                'headerComponents' => []
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CrudReadRequest $request, $id)
    {
        // Eager loads relations.
        $query = $this->query();
        foreach ($this->getForm('form')->getRegisteredFields() as $field) {
            if ($field instanceof RelationField && !$field instanceof MediaField) {
                $query->with($field->getRelationName());
            }
        }

        // Find model.
        $model = $query->findOrFail($id);
        // Set last_edit attribute.
        $model->last_edit;

        // Append media.
        foreach ($this->getForm('form')->getRegisteredFields() as $field) {
            if ($field instanceof MediaField) {
                $model->append($field->id);
            }
        }

        // Load config attributes.
        $config = $this->config->get(
            'form',
            'route_prefix',
            'names',
            'permissions',
            'expandFormContainer'
        );
        $config['expand'] = $config['expandFormContainer'];

        // Set readonly if the user has no update permission for this crud.
        foreach ($config['form']->getRegisteredFields() as $field) {
            if (!$config['permissions']['update']) {
                $field->readonly();
            }
        }

        // Get preview route.
        if ($this->config->hasMethod('previewRoute')) {
            $config['preview_route'] = $this->config->previewRoute($model);
        }

        $previous = $this->model::where('id', '<', $id)->orderBy('id', 'desc')->select('id')->first()->id ?? null;
        $next = $this->model::where('id', '>', $id)->orderBy('id')->select('id')->first()->id ?? null;

        return view('fjord::app')->withComponent($this->config->formComponent)
            ->withTitle($this->config->names['singular'])
            ->withProps([
                'crud-model' => crud($model),
                'config' => $config,
                'backRoute' => $this->config->route_prefix,
                'nearItems' => [
                    'next' => $next,
                    'previous' => $previous
                ],
                'controls' => [],
            ]);
    }

    /**
     * Sort.
     *
     * @param CrudUpdateRequest $request
     * @return void
     */
    public function order(CrudUpdateRequest $request)
    {
        $ids = $request->ids ?? abort(404);

        $models = $this->query()
            ->whereIn('id', $ids)
            ->get();

        foreach ($ids as $order => $id) {
            $model = $models->where('id', $id)->first();

            if (!$model) {
                continue;
            }
            $model->{$this->config->orderColumn} = $order;
            $model->save();
        }
    }
}
