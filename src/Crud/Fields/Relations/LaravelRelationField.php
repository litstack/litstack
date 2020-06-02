<?php

namespace Fjord\Crud\Fields\Relations;

use Closure;
use Fjord\Vue\Table;
use Fjord\Crud\RelationField;
use Fjord\Crud\Fields\Relations\Concerns\RelationForm;

class LaravelRelationField extends RelationField
{
    use Concerns\ManagesRelatedConfig;

    /**
     * Relation query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Index query modifier.
     *
     * @var Closure|null
     */
    protected $previewModifier;

    /**
     * Relation model class.
     *
     * @var string
     */
    protected $relatedModelClass;

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['preview'];

    /**
     * Create new Field instance.
     *
     * @param string $id
     * @param string $model
     * @param string|null $routePrefix
     */
    public function __construct(string $id, string $model, $routePrefix, $form)
    {
        parent::__construct($id, $model, $routePrefix, $form);

        $this->initializeRelationField();
    }

    /**
     * Set default field attributes.
     *
     * @return array
     */
    public function setDefaultAttributes()
    {
        parent::setDefaultAttributes();


        $this->confirm();
        $this->small(false);
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
        $relatedInstance = $this->getRelatedInstance();

        $this->relatedModelClass = get_class($relatedInstance);
        $this->query = $this->getRelatedModelClass()::query();

        $this->loadRelatedConfig($this->getRelatedModelClass());
        $this->setOrderDefaults();
        $this->setAttribute('model', $this->getRelatedModelClass());

        // Set relation attributes.
        if (method_exists($this, 'setRelationAttributes')) {
            $this->setRelationAttributes($this->getRelationQuery(new $this->model));
        }

        return $this;
    }

    /**
     * Get related model class.
     *
     * @return string
     */
    public function getRelatedModelClass()
    {
        return $this->relatedModelClass;
    }

    /**
     * Set order defaults.
     *
     * @return void
     */
    protected function setOrderDefaults()
    {
        $orders = $this->getRelationQuery(new $this->model)->getQuery()->getQuery()->orders;

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
     * Get relation method name.
     *
     * @return string
     */
    public function getRelationName()
    {
        return $this->id;
    }

    /**
     * Set index query modifier.
     *
     * @param Closure $closure
     * @return self
     */
    public function query(Closure $closure)
    {
        $this->previewModifier = $closure;

        return $this;
    }

    /**
     * Get relation query for model.
     *
     * @param mixed $model
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        return $this->modifyQuery(
            $model->{$this->id}()
        );
    }

    /**
     * Get related model instance.
     *
     * @return mixed
     */
    protected function getRelatedInstance()
    {
        return $this->getRelationQuery(
            new $this->model
        )->getRelated();
    }

    /**
     * Modify preview query with eager loads and accessors to append.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function modifyQuery($query)
    {
        if (!$this->previewModifier instanceof Closure) {
            return $query;
        }

        $modifier = $this->previewModifier;
        $modifier($query);

        return $query;
    }

    /**
     * Add edit form.
     *
     * @param Closure $closure
     * @return void
     */
    public function form(Closure $closure)
    {
        $form = new RelationForm($this->getRelatedModelClass());

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
        $table = new Table;

        $closure($table);

        $this->attributes['preview'] = $table;

        return $this;
    }

    /**
     * Set query initial builder.
     *
     * @param Closure $closure
     * @return void
     */
    public function filter(Closure $closure)
    {
        $closure($this->query);

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
     * Small table.
     *
     * @param boolean $small
     * @return self
     */
    public function small($small = true)
    {
        $this->setAttribute('small', $small);

        return $this;
    }

    /**
     * Confirm delete in modal.
     *
     * @param boolean $confirm
     * @return self
     */
    public function confirm($confirm = true)
    {
        $this->setAttribute('confirm', $confirm);

        return $this;
    }
}
