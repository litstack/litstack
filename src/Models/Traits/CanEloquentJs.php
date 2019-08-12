<?php

namespace AwStudio\Fjord\Models\Traits;

trait CanEloquentJs
{
    public $eloquentRelations = [];

    public function eloquentJs()
    {
        $this->withFields();
        return eloquentJs($this, get_class($this));
    }

    public function scopeEloquentJs($query, $method, $data = null)
    {
        $eloquentRelations = $this->eloquentRelations;
        if ($data) {
            $model = $query->$method($data);
        } else {
            $model = $query->$method();
        }
        if (method_exists($model, 'withFields')) {
            $model->withFields();
        }
        $model->eloquentRelations = $eloquentRelations;

        return eloquentJs($model, get_class($this));
    }

    public function scopewithRelation($query, $key)
    {
        $query->with($key);

        $this->eloquentRelations[$key] = get_class($this->$key()->getRelated());

        return $query;
    }
}
