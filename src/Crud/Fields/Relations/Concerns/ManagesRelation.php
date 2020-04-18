<?php

namespace Fjord\Crud\Fields\Relations\Concerns;

use Closure;
use Fjord\Vue\Crud\RelationTable;
use Illuminate\Database\Eloquent\Builder;

trait ManagesRelation
{
    /**
     * Relation query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Relation model class.
     *
     * @var string
     */
    protected $relationModel;

    /**
     * Build relation index table.
     *
     * @param Closure $closure
     * @return self
     */
    public function preview(Closure $closure)
    {
        $table = new RelationTable;

        $this->attributes['preview'] = $table;

        $closure($table);

        return $this;
    }

    /**
     * Set model and query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder|string $model
     * @return self
     */
    public function model($model)
    {
        if ($model instanceof Builder) {
            $this->query = $model;
            $model = get_class($model);
        } else {
            $this->query = $model::query();
        }

        $this->relationModel = $model;

        $this->attributes['model'] = $model;

        // Set model route_prefix for api calls.
        $this->attributes['route_prefix'] = $model::config()->route_prefix;

        // Set relation attributes
        if (method_exists($this, 'setRelationAttributes')) {
            $this->setRelationAttributes($model);
        }

        return $this;
    }

    /**
     * Get relation query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->query;
    }
}
