<?php

namespace Fjord\EloquentJs;

use Throwable;

class EloquentJs
{
    private $infoModel;

    private $relations = [];

    public function __construct($model, $class, string $type = 'fjord')
    {
        $this->type = $type;
        $this->infoModel = with(new $class());
        $this->model = $model;
        $this->setRelations();
    }

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

    protected function setRelation($model)
    {
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
            'fillable' => $this->getFillables(),
            'relations' => $this->relations,
            'data' => $this->model,
            'translatable' => is_translatable($this->infoModel),
            'model' => get_class($this->infoModel),
            'route' => $this->getRoute(),
            'collection' => $this->isCollection(),
        ]);
    }

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

    public function getFillables()
    {
        if (!is_translatable($this->infoModel)) {
            return $this->getFillablesFromModel($this->infoModel);
        }
        $modelName = $this->infoModel->getTranslationModelName();
        return $this->getFillablesFromModel(new $modelName());
    }

    protected function getFillablesFromModel($model)
    {
        $fillables = [];
        foreach ($model->getFillable() as $key) {
            $fillables[$key] = $model->$key;
        }
        return $fillables;
    }
}
