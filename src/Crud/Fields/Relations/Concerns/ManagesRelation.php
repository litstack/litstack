<?php

namespace Fjord\Crud\Fields\Relations\Concerns;

use Closure;
use Fjord\Vue\Crud\PreviewTable;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;
use InvalidArgumentException;

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
     * @param string|null $routePrefix
     */
    public function __construct(string $id, string $model, $routePrefix)
    {
        parent::__construct($id, $model, $routePrefix);

        $this->initializeRelationField();
    }

    /**
     * Add edit form.
     *
     * @param Closure $closure
     * @return void
     */
    public function edit(Closure $closure)
    {
        $form = new RelationForm($this->related);

        $closure($form);

        $this->attributes['edit'] = $form;

        return $this;
    }

    /**
     * Build relation index table.
     *
     * @param Closure $closure
     * @return self
     */
    public function preview(Closure $closure)
    {
        $table = new PreviewTable;

        $this->attributes['preview'] = $table;

        $closure($table);

        return $this;
    }

    /**
     * Set model and query builder.
     *
     * @return self
     * 
     * @throws \InvalidArgumentException
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

        if ($relation->getTable() == 'form_relations') {
            throw new InvalidArgumentException("The relation Field should be used for Laravel relations, for Fjord relations use oneRelation or manyRelation.");
        }

        $model = get_class($related);

        $this->query = $related::query();
        $this->related = $model;
        $this->attributes['model'] = $model;

        // Set relation attributes.
        if (method_exists($this, 'setRelationAttributes')) {
            $this->setRelationAttributes($relation);
        }

        return $this;
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
