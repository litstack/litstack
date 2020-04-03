<?php

namespace AwStudio\Fjord\EloquentJs;

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

            if (!$this->model->eloquentRelations) {
                return;
            }

            $this->model->append('eloquentJs');

            return;
        }

        foreach ($this->model as $model) {
            if (!property_exists($model, 'eloquentRelations')) {
                continue;
            }

            if (!$model->eloquentRelations) {
                continue;
            }

            $model->append('eloquentJs');
        }


        return;
        foreach ($this->model->eloquentRelations as $name => $class) {
            // TODO: only works for hasMany
            if ($this->model->$name->count() == 0) {
                $this->relations[$name] = collect([]);
                continue;
            }

            $this->relations[$name] = eloquentJs($this->model->$name, $class);
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
            'route' => $this->infoModel->getTable(),
            'collection' => $this->isCollection(),
        ]);
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
