<?php

namespace Fjord\Crud\Controllers;

use Fjord\Crud\Fields\Media\MediaField;
use Fjord\Crud\Models\FjordFormModel;
use Fjord\Crud\RelationField;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudDeleteRequest;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class CrudController extends CrudBaseController
{
    /**
     * The Model Class e.g. App\Models\Post.
     *
     * @var string
     */
    protected $model;

    /**
     * Authorize request for permission operation and authenticated fjord-user.
     * Operations: create, read, update, delete.
     *
     * @param \Fjord\User\Models\FjordUser $user
     * @param string                       $operation
     *
     * @return bool
     */
    //abstract public function authorize(FjordUser $user, string $operation, $id = null);

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
     * @param int             $id
     *
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
     *
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
     *
     * @return void
     */
    public function destroy(CrudDeleteRequest $request, $id)
    {
        $this->delete(
            $this->query()->where('id', $id)
        );
    }

    /**
     * Delete action.
     *
     * @param  CrudDeleteRequest            $request
     * @param  Collection                   $models
     * @return Illuminate\Http\JsonResponse
     */
    public function deleteAction(CrudDeleteRequest $request, Collection $models)
    {
        $models->map(fn ($item) => $item->delete());

        return success(
            __f_choice('messages.deleted_items', count($models))
        );
    }

    /**
     * Show Crud index.
     *
     * @param CrudReadRequest $request
     *
     * @return View
     */
    public function index(CrudReadRequest $request)
    {
        $config = $this->config->get(
            'route_prefix', 'names', 'permissions'
        );

        $page = $this->config->index
            ->title($this->config->names['plural'])
            ->bind([
                'config' => $config,
            ]);

        $page->navigationRight()->component('fj-crud-create-button');

        return $page;
    }

    /**
     * Show Crud create.
     *
     * @param CrudCreateRequest $request
     *
     * @return void
     */
    public function create(CrudCreateRequest $request)
    {
        $config = $this->config->get(
            'show',
            'names',
            'permissions',
            'route_prefix'
        );
        $config['form'] = $config['show'];
        unset($config['show']);

        $page = $this->config->show
            ->title($this->config->names['singular'])
            ->bindToView([
                'model'  => new $this->model(),
                'config' => $this->config,
            ])
            ->bindToVue([
                'crud-model' => crud(new $this->model()),
                'config'     => $config,
            ]);

        return $page;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CrudReadRequest $request, $id)
    {
        // Eager loads relations.
        $query = $this->query();
        foreach ($this->config->show->getRegisteredFields() as $field) {
            if ($field instanceof RelationField && ! $field instanceof MediaField) {
                $query->with($field->getRelationName());
            }
        }

        // Find model.
        $model = $query->findOrFail($id);
        // Set last_edit attribute.
        $model->last_edit;

        // Append media.
        if (! $model instanceof FjordFormModel) {
            foreach ($this->config->show->getRegisteredFields() as $field) {
                if ($field instanceof MediaField) {
                    $model->append($field->id);
                }
            }
        }

        // Load config attributes.
        $config = $this->config->get(
            'show',
            'route_prefix',
            'names',
            'permissions',
        );
        $config['form'] = $config['show'];
        unset($config['show']);

        // Set readonly if the user has no update permission for this crud.
        foreach ($config['form']->getRegisteredFields() as $field) {
            if (! $config['permissions']['update']) {
                $field->readonly();
            }
        }

        // Get preview route.
        if ($this->config->hasMethod('previewRoute')) {
            $config['preview_route'] = $this->config->previewRoute($model);
        }

        $previous = $this->model::where('id', '<', $id)->orderBy('id', 'desc')->select('id')->first()->id ?? null;
        $next = $this->model::where('id', '>', $id)->orderBy('id')->select('id')->first()->id ?? null;

        $page = $this->config->show
            ->title($this->config->names['singular'])
            ->bindToView([
                'model'  => $model,
                'config' => $this->config,
            ])
            ->bindToVue([
                'crud-model' => crud($model),
                'config'     => $config,
            ]);

        // Show near items.
        $page->navigationLeft()->component('fj-crud-show-near-items')->bind([
            'next'         => $next,
            'previous'     => $previous,
            'route-prefix' => $this->config->routePrefix,
        ]);

        return $page;
    }

    /**
     * Sort.
     *
     * @param  CrudUpdateRequest $request
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

            if (! $model) {
                continue;
            }
            $model->{$this->config->orderColumn} = $order;
            $model->save();
        }
    }
}
