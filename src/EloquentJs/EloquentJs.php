<?php

namespace Fjord\EloquentJs;

use Throwable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;


class EloquentJs
{
    /**
     * Model instance.
     *
     * @var mixed
     */
    private $infoModel;

    /**
     * Relations.
     *
     * @var array
     */
    private $relations = [];

    /**
     * Create new EloquentJs instance.
     *
     * @param mixed $model
     * @param string $route
     * @param string $type
     */
    public function __construct($model, string $route, string $type = 'fjord')
    {
        $this->route = $route;
        $this->type = $type;
        $this->setInfoModel($model);
        $this->model = $model;
        $this->setRelations();
    }

    /**
     * Set info model instance.
     *
     * @param mixed $model
     * @return void
     */
    public function setInfoModel($model)
    {
        if (is_string($model)) {
            return $this->infoModel = new $model;
        }
        if ($model instanceof Collection || $model instanceof EloquentCollection) {
            return $this->infoModel = $model->first();
        }

        $this->infoModel = $model;
    }

    /**
     * Set relations.
     *
     * @return void
     */
    protected function setRelations()
    {
        if (!$this->model) {
            return;
        }

        if (get_class($this->model) == get_class($this->infoModel)) {
            if (!property_exists($this->model, 'eloquentRelations')) {
                return;
            }

            if (empty($model->getEloquentJsRelations())) {
                return;
            }

            $this->model->append('eloquentJs');

            return;
        }

        foreach ($this->model as $model) {
            if (empty($model->getEloquentJsRelations())) {
                continue;
            }

            $model->append('eloquentJs');
        }
    }

    /**
     * Get config for javascript.
     *
     * @return array
     */
    public function toArray()
    {
        return collect([
            'type' => $this->type,
            'relations' => $this->relations,
            'data' => $this->model,
            'translatable' => is_translatable($this->infoModel),
            'model' => get_class($this->infoModel),
            'route' => $this->getRoute(),
            'collection' => $this->isCollection(),
        ]);
    }

    /**
     * Get route.
     *
     * @return void
     */
    public function getRoute()
    {
        try {
            $config = @$this->infoModel->config();
        } catch (Throwable $e) {
            return 'crud/' . $this->infoModel->getTable();
        }
        return $config->route_prefix;
    }

    /**
     * Check if model is a collection instance.
     *
     * @return boolean
     */
    public function isCollection()
    {
        return $this->model instanceof Collection;
    }
}
