<?php

namespace AwStudio\Fjord\EloquentJs;

use Illuminate\Database\Eloquent\Collection;

trait CanEloquentJs
{
    public $eloquentRelations = [];

    public function getEloquentJsAttribute()
    {
        $relations = [];

        foreach ($this->eloquentRelations as $relationName => $class) {
            $relations[$relationName] = eloquentJs($this->$relationName, $class);
        }

        return $relations;
    }

    public function eloquentJs(String $type = 'fjord')
    {
        if (!array_key_exists('form_fields', $this->appends)) {
            $this->append('form_fields');
        }

        return eloquentJs($this, get_class($this), $type);
    }

    public function scopeEloquentJs($query, String $type = 'fjord', $method, $data = null)
    {
        $eloquentRelations = $this->eloquentRelations;
        if ($data) {
            $model = $query->$method($data);
        } else {
            $model = $query->$method();
        }

        // Append form fields if needed.
        if ($type == 'fjord') {
            if (get_class($model) == Collection::class) {
                $model->map(function ($item) {
                    if (!array_key_exists('form_fields', $item->appends)) {
                        $item->append('form_fields');
                    }
                });
            } else {
                if (!array_key_exists('form_fields', $model->appends)) {
                    $model->append('form_fields');
                }
            }
        }

        $model->eloquentRelations = $eloquentRelations;

        return eloquentJs($model, get_class($this), $type);
    }

    public function scopewithRelation($query, $key)
    {
        $query->with($key);

        $this->eloquentRelations[$key] = get_class($this->$key()->getRelated());

        return $query;
    }

    public function withRelation($key)
    {
        $this->with($key);

        $this->eloquentRelations[$key] = get_class($this->$key()->getRelated());

        return $this;
    }
}
