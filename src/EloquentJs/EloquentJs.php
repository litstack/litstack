<?php

namespace AwStudio\Fjord\EloquentJs;

class EloquentJs
{
    private $infoModel;

    private $relations = [];

    public function __construct($model, $class)
    {
        $this->infoModel = new $class();
        $this->model = $model;
        $this->setRelations();
    }

    protected function setRelations()
    {
        if(! property_exists($this->model, 'eloquentRelations')) {
            return;
        }

        foreach($this->model->eloquentRelations as $name => $class) {
            // TODO: only works for hasMany
            if($this->model->$name->count() == 0) {
                $this->relations[$name] = collect([]);
                continue;
            }

            $this->relations[$name] = eloquentJs($this->model->$name, $class);
        }
    }

    public function toArray()
    {
        return collect([
            'fillable' => $this->getFillables(),
            'relations' => $this->relations,
            'data' => $this->model,
            'translatable' => is_translateable($this->infoModel),
            'model' => get_class($this->infoModel),
            'route' => $this->infoModel->getTable()
        ]);
    }

    public function getFillables()
    {
        if(! is_translateable($this->infoModel)) {
            return $this->getFillablesFromModel($this->infoModel);
        }
        $modelName = $this->infoModel->getTranslationModelName();
        return $this->getFillablesFromModel(new $modelName());
    }

    protected function getFillablesFromModel($model)
    {
        $fillables = [];
        foreach($model->getFillable() as $key) {
            $fillables[$key] = $model->$key;
        }
        return $fillables;
    }
}
