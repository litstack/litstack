<?php

namespace Fjord\Crud\Fields\Relations\Concerns;

use Closure;
use InvalidArgumentException;
use Fjord\Vue\Crud\RelationTable;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;

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
    protected $related;

    /**
     * Create new Field instance.
     *
     * @param string $id
     * @param string $model
     */
    public function __construct(string $id, string $model)
    {
        parent::__construct($id, $model);

        $this->initializeRelationField();
    }

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
     * @return self
     */
    protected function initializeRelationField()
    {
        if (
            $this instanceof ManyRelation
            || $this instanceof OneRelation
        ) {
            return;
        }

        $relation = $this->getRelation(
            new $this->model
        );

        $related = $relation->getRelated();

        $model = get_class($related);

        $this->query = $related::query();

        $this->related = $model;

        $this->attributes['model'] = $model;

        // Set model route_prefix for api calls.
        $config = $this->getModelConfig($model);
        $this->attributes['route_prefix'] = $config->route_prefix ?? null;

        // Set relation attributes.
        if (method_exists($this, 'setRelationAttributes')) {
            $this->setRelationAttributes($relation);
        }

        return $this;
    }

    /**
     * Throw missing config exception.
     *
     * @return void
     * 
     * @throws \InvalidArgumentException
     */
    protected function throwMissingConfigException()
    {
        throw new InvalidArgumentException(
            sprintf(
                "%s relation on %s::%s requires missing Crud config for model %s.",
                class_basename(static::class),
                $this->model,
                $this->attributes['id'],
                $this->related
            )
        );
    }

    protected function getModelConfig($model)
    {
        // Try model name.
        $modelName = lcfirst(last(explode('\\', $model)));;
        if ($config = fjord()->config("crud.{$modelName}")) {
            return $config;
        }

        // TODO: Talk about this.
        // Try table name.
        /*
        $tableName = (new $model)->getTable();
        if ($config = fjord()->config("crud.{$tableName}")) {
            return $config;
        }
        */
    }

    /**
     * Get relation query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set query initial builder.
     *
     * @param Builder $query
     * @return void
     */
    public function query(Builder $query)
    {
        $this->query = $query;

        return $this;
    }
}
