<?php

namespace Fjord\Crud\Fields\Relations\Concerns;

use Closure;
use InvalidArgumentException;
use Fjord\Support\Facades\Crud;
use Fjord\Support\Facades\Fjord;
use Fjord\Vue\Crud\PreviewTable;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Fields\Concerns\FieldHasForm;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;

trait ManagesRelation
{
    use ManagesRelatedConfig, FieldHasForm;

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
     * Index query modifier.
     *
     * @var Closure|null
     */
    protected $previewModifier;

    /**
     * Relation builder instance.
     *
     * @var mixed
     */
    protected $relation;

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
     * Set index query modifier.
     *
     * @param Closure $closure
     * @return self
     */
    public function previewQuery(Closure $closure)
    {
        $this->previewModifier = $closure;

        return $this;
    }

    /**
     * Add edit form.
     *
     * @param Closure $closure
     * @return void
     */
    public function form(Closure $closure)
    {
        $form = new RelationForm($this->related);

        $form->setRoutePrefix(
            strip_slashes($this->getRelatedConfig()->routePrefix . '/{id}')
        );

        $closure($form);

        $this->attributes['form'] = $form;

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

        $closure($table);

        // Add open relation edit page if user has permission.
        if ($this->relatedConfig->permissions['read']) {
            $route = Fjord::url($this->relatedConfig->routePrefix . '/{id}/edit');
            //$table->col("<a href=\"{$route}\"><i class=\"ml-4 fas fa-eye text-secondary\"></i></a>")->small();
        }

        $this->attributes['preview'] = $table;

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

        if (method_exists($relation, 'getTable')) {
            if ($relation->getTable() == 'form_relations') {
                throw new InvalidArgumentException("The relation Field should be used for Laravel relations, for Fjord relations use oneRelation or manyRelation.");
            }
        }


        $model = get_class($related);

        $this->query = $related::query();
        $this->related = $model;
        $this->attributes['model'] = $model;

        $this->loadRelatedConfig($model);
        $this->setRelation();

        // Set relation attributes.
        if (method_exists($this, 'setRelationAttributes')) {
            $this->setRelationAttributes($relation);
        }

        return $this;
    }

    /**
     * Set relation instance.
     *
     * @return void
     */
    protected function setRelation()
    {
        $this->relation = $this->relation(new $this->model, $query = true);

        $orders = $this->relation->getQuery()->getQuery()->orders;

        if (empty($orders)) {
            return;
        }

        $order = $orders[0];
        if (method_exists($this->relation, 'getTable')) {
            $orderColumn = str_replace($this->relation->getTable() . '.', '', $order['column']);
        } else {
            $orderColumn = $order['column'];
        }

        $this->setAttribute('orderColumn', $orderColumn);
        $this->setAttribute('orderDirection', $order['direction']);
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
