<?php

namespace Fjord\Crud\EloquentJs;

use Fjord\Support\VueProp;
use BadMethodCallException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class EloquentJs extends VueProp
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
    protected $relations = [];

    /**
     * Create new EloquentJs instance.
     *
     * @param mixed $model
     * @param string $route
     * @param string $type
     */
    public function __construct($model, string $route, array $with = [], string $type = 'fjord')
    {
        $this->route = $route;
        $this->type = $type;
        $this->relations = $with;
        $this->initInfoModel($model);
        $this->model = $model;
        $this->setRelations();
    }

    /**
     * Set info model instance.
     *
     * @param mixed $model
     * @return void
     */
    public function initInfoModel($model)
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

        if (empty($this->relations)) {
            return;
        }

        $models = $this->isCollection()
            ? $this->model
            : collect([$this->model]);

        foreach ($models as $model) {
            $eloquentJs = [];
            foreach ($this->relations as $relation) {
                try {
                    $related = $model->$relation();
                } catch (BadMethodCallException $e) {
                    // Relation does not exist.
                    continue;
                }

                $eloquent = (new self($model->$relation, ''))->setInfoModel($related->getRelated());
                $eloquentJs[$relation] = $eloquent->toArray();
            }
            $model->setAttribute('eloquentJs', $eloquentJs);
        }
    }

    /**
     * Set info model instance.
     *
     * @param mixed $model
     * @return void
     */
    public function setInfoModel($model)
    {
        $this->infoModel = $model;

        return $this;
    }

    /**
     * Get route.
     *
     * @return void
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Check if model is a collection instance.
     *
     * @return boolean
     */
    public function isCollection()
    {
        return $this->model instanceof Collection ||
            $this->model instanceof EloquentCollection;
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [
            'type' => $this->type,
            'relations' => $this->relations,
            'data' => $this->model,
            'translatable' => $this->infoModel ? is_translatable($this->infoModel) : false,
            'model' => $this->infoModel ? get_class($this->infoModel) : '',
            'route' => $this->getRoute(),
            'collection' => $this->isCollection(),
        ];
    }
}
