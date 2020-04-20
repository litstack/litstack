<?php

namespace Fjord\EloquentJs;

use Illuminate\Database\Eloquent\Collection;

trait CanEloquentJs
{
    protected $eloquentJsRelations = [];

    public function getEloquentJsRelations()
    {
        return $this->eloquentJsRelations;
    }

    public function getEloquentJsAttribute()
    {
        $relations = [];

        foreach ($this->eloquentJsRelations as $relationName => $class) {
            $relations[$relationName] = eloquentJs($this->$relationName, '');
        }

        return $relations;
    }

    public function eloquentJs(String $type = 'fjord')
    {
        if (!array_key_exists('fields', $this->appends)) {
            $this->append('fields');
        }

        return eloquentJs($this, get_class($this), $type);
    }

    public function scopeEloquentJs($query, String $type = 'fjord', $method, $data = null)
    {
        $eloquentJsRelations = $this->eloquentJsRelations;
        if ($data) {
            $model = $query->$method($data);
        } else {
            $model = $query->$method();
        }

        // Append form fields if needed.
        if ($type == 'fjord') {
            if (get_class($model) == Collection::class) {
                $model->map(function ($item) {
                    if (!array_key_exists('fields', $item->appends)) {
                        $item->append('fields');
                    }
                });
            } else {
                if (!array_key_exists('fields', $model->appends)) {
                    $model->append('fields');
                }
            }
        }

        $model->eloquentJsRelations = $eloquentJsRelations;

        return eloquentJs($model, get_class($this), $type);
    }

    public function scopewithRelation($query, $key)
    {
        $query->with($key);

        $this->eloquentJsRelations[$key] = get_class($this->$key()->getRelated());

        return $query;
    }

    public function withRelation($key)
    {
        $this->with($key);

        $this->eloquentJsRelations[$key] = get_class($this->$key()->getRelated());

        return $this;
    }
}
