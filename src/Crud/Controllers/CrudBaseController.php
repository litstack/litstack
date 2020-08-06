<?php

namespace Fjord\Crud\Controllers;

use Fjord\Crud\Actions\ActionResolver;
use Fjord\Crud\Api\ApiLoader;
use Fjord\Crud\Api\ApiRequest;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Support\IndexTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class CrudBaseController
{
    use Concerns\ManagesConfig;

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query(): Builder;

    /**
     * Fill model on store.
     *
     * @param  mixed $model
     * @return void
     */
    public function fillOnStore($model)
    {
        //
    }

    /**
     * Fill model on update.
     *
     * @param  mixed $model
     * @return void
     */
    public function fillOnUpdate($model)
    {
        //
    }

    /**
     * Delete by query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function delete(Builder $query)
    {
        $query->delete();
    }

    /**
     * Run action.
     *
     * @param  Request $request
     * @param  string  $key
     * @return void
     */
    public function runAction(Request $request, $key)
    {
        if (! $index = $this->config->index) {
            abort(404, debug('Missing [index] configuration on '.get_class($this->config->getConfig())));
        }

        if (! $table = $index->getTable()) {
            abort(404, debug('Missing index table configuration in '.get_class($this->config->getConfig())));
        }

        if (! $action = $table->getAction($key)) {
            abort(404, debug("Missing table action [{$key}] in ".get_class($this->config->getConfig())));
        }

        $models = $this->query()->whereIn('id', $request->ids ?? [])->get();

        return $action->resolve($models);
    }

    /**
     * Find or faild model by identifier.
     *
     * @param  string|int $id
     * @return void
     */
    public function findOrFail($id)
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Perform crud api call.
     *
     * @param  Request        $request
     * @param  ActionResolver $resolver
     * @return mixed
     */
    public function api(Request $request)
    {
        $api = app()->make(ApiRequest::class, [
            'controller' => $this,
            'loader'     => new ApiLoader($this, $this->getConfig()),
        ]);

        return $api->handle();
    }

    /**
     * Load index table items.
     *
     * @param  CrudReadRequest $request
     * @return array           $items
     */
    public function indexTable(CrudReadRequest $request)
    {
        $table = $this->config->index->getTable();
        $query = $table->getQuery($this->query());

        $index = IndexTable::query($query)
            ->request($request)
            ->search($table->getAttribute('search'))
            ->casts($table->getCasts())
            ->get();

        $index['items'] = crud($index['items']);

        return $index;
    }

    /**
     * Get crud model class.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }
}
