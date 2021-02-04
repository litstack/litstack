<?php

namespace Ignite\Crud\Controllers;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\Actions\ActionResolver;
use Ignite\Crud\Api\ApiLoader;
use Ignite\Crud\Api\ApiRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class CrudBaseController
{
    use Concerns\ManagesConfig,
        Concerns\ManagesQuery;

    /**
     * Model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Create new CrudBaseController instance.
     *
     * @param  ConfigHandler|null $config
     * @return void
     */
    public function __construct(ConfigHandler $config = null)
    {
        if (is_null($config)) {
            $this->config = $this->loadConfig();
        } else {
            $this->config = $config;
        }

        $this->model = $this->config->model ?? null;
    }

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

        $models = $this->getQuery()->whereIn('id', $request->ids ?? [])->get();

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
        return $this->getQuery()->findOrFail($id);
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
     * Get crud model class.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get form name from requset.
     *
     * @param  Request $request
     * @return string
     */
    protected function getFormName(Request $request)
    {
        return last(explode('.', $request->route()->getName()));
    }
}
