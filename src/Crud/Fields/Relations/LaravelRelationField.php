<?php

namespace Ignite\Crud\Fields\Relations;

use Closure;
use Ignite\Crud\RelationField;
use Ignite\Exceptions\Traceable\InvalidArgumentException as LitstackInvalidArgumentException;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Crud;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LaravelRelationField extends RelationField
{
    /**
     * Relation query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Index query modifier.
     *
     * @var array
     */
    protected $previewModifier = [];

    /**
     * Relation model class.
     *
     * @var string
     */
    protected $relatedModelClass;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Create new Field instance.
     *
     * @param string      $id
     * @param string      $model
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
    public function mount()
    {
        parent::mount();

        $this->search('title');
        $this->confirm();
        $this->small(false);
    }

    /**
     * Set model and query builder.
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    protected function initializeRelationField()
    {
        $relatedInstance = $this->getRelatedInstance();

        $this->setRelatedModelClass(get_class($relatedInstance));
        $this->query = $this->getRelatedModelClass()::query();

        $this->setOrderDefaults();

        // Set relation attributes.
        if (method_exists($this, 'setRelationAttributes')) {
            $this->setRelationAttributes($this->getRelationQuery(new $this->model()));
        }

        return $this;
    }

    /**
     * Set related model class.
     *
     * @param  string $model
     * @return $this
     */
    protected function setRelatedModelClass(string $model)
    {
        $this->relatedModelClass = $model;

        $relatedConfigKey = 'crud.'.Str::snake(class_basename($model));

        if (Config::exists($relatedConfigKey) && ! $this->relatedConfig) {
            $this->use($relatedConfigKey);
        }

        if (! $this->names) {
            $this->names(Crud::names($model));
        }
    }

    /**
     * Merge related config.
     *
     * @param  string $configKey
     * @return void
     */
    protected function mergeRelatedConfig($configKey)
    {
        if (! Config::exists($configKey)) {
            return;
        }
        $relatedConfig = Config::get($configKey);

        if (! $this->related_route_prefix) {
            $this->routePrefix($relatedConfig->routePrefix());
        }
        if ($relatedConfig->has('index')) {
            $this->setAttribute('preview', $relatedConfig->index->getTable()->getTable());
        }
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
        $orders = $this->getRelationQuery(new $this->model())->getQuery()->getQuery()->orders;

        if (empty($orders)) {
            return;
        }

        $order = $orders[0];
        if (method_exists($this->relation, 'getTable')) {
            $orderColumn = str_replace($this->relation->getTable().'.', '', $order['column']);
        } else {
            $orderColumn = $order['column'];
        }

        $this->setAttribute('orderColumn', $orderColumn);
        $this->setAttribute('orderDirection', $order['direction']);
    }

    /**
     * Set index query modifier.
     *
     * @param  Closure $closure
     * @return self
     */
    public function query(Closure $closure)
    {
        $this->previewModifier[] = $closure;

        return $this;
    }

    /**
     * Use related config.
     *
     * @param  string $config
     * @return void
     */
    public function use(string $config)
    {
        $this->relatedConfig = Config::get($config);

        if (! $this->relatedConfig) {
            throw new InvalidArgumentException("Couldn't find config {$config}");
        }

        if ($this->relatedConfig->model != $this->getRelatedModelClass()
        && $this->getRelatedModelClass() != null) {
            throw new LitstackInvalidArgumentException(
                "Invalid CRUD Config {$config} for Model {$this->relatedConfig->model}, must be ".$this->getRelatedModelClass()." for relation [{$this->id}].",
                [
                    'function' => 'use',
                ]
            );
        }

        if (method_exists($this, 'model')) {
            $this->model($this->relatedConfig->model);
        }
        if ($this->relatedConfig->has('index')) {
            if ($this->relatedConfig->index) {
                $table = clone $this->relatedConfig->index->getTable();

                $this->search($table->search);

                $this->setAttribute(
                    'preview', $table->getBuilder()->disableLinks()
                );
            }
        }

        $this->routePrefix($this->relatedConfig->routePrefix());

        return $this;
    }

    /**
     * Get relation query for model.
     *
     * @param  mixed $model
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
            new $this->model()
        )->getRelated();
    }

    /**
     * Modify preview query with eager loads and accessors to append.
     *
     * @param  Builder $query
     * @return Builder
     */
    protected function modifyQuery($query)
    {
        $query->withCasts($this->casts);

        foreach ($this->previewModifier as $modifier) {
            $modifier($query);
        }

        return $query;
    }

    /**
     * Build relation index table.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function preview(Closure $closure)
    {
        $builder = new RelationColumnBuilder($this->relatedConfig);

        // In order for the column builder to set a cast when creating a money
        // field, it is necessary to pass the field instance to the column builder.
        $builder->setParent($this);

        $closure($builder);

        $this->attributes['preview'] = $builder;

        return $this;
    }

    /**
     * Singular and plural name.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function names(array $names)
    {
        if (! array_key_exists('singular', $names) || ! array_key_exists('plural', $names)) {
            throw new InvalidArgumentException('Singular and plural name may be present.');
        }

        $this->setAttribute('names', $names);

        return $this;
    }

    /**
     * Set prefix to related config.
     *
     * @param  string $routePrefix
     * @return $this
     */
    public function routePrefix(string $routePrefix)
    {
        $this->setAttribute('related_route_prefix', $routePrefix);

        return $this;
    }

    /**
     * Set search keys.
     *
     * @param  array ...$keys
     * @return $this
     */
    public function search(...$keys)
    {
        $keys = Arr::wrap($keys);
        if (count($keys) == 1) {
            if (is_array($keys[0])) {
                $keys = $keys[0];
            }
        }

        $this->setAttribute('search', $keys);

        return $this;
    }

    /**
     * Set query initial builder.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function filter(Closure $closure)
    {
        $closure($this->query);

        return $this;
    }

    /**
     * Set cast for the given attribute.
     *
     * @param  string $attribute
     * @param  string $cast
     * @return $this
     */
    public function cast($attribute, $cast)
    {
        $this->casts[$attribute] = $cast;

        return $this;
    }

    /**
     * Set cast for the given attribute.
     *
     * @param  array $casts
     * @return $this
     */
    public function casts(array $casts)
    {
        foreach ($casts as $attribute => $cast) {
            $this->cast($attribute, $cast);
        }

        return $this;
    }

    /**
     * Get casted attributes.
     *
     * @return array
     */
    public function getCasts()
    {
        return $this->casts;
    }

    /**
     * Get relation query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return $this->modifyQuery($this->query);
    }

    /**
     * Small table.
     *
     * @param  bool $small
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
     * @param  bool $confirm
     * @return self
     */
    public function confirm($confirm = true)
    {
        $this->setAttribute('confirm', $confirm);

        return $this;
    }

    /**
     * Add form to the relation.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function form(Closure $closure)
    {
        if (! $this->relatedModelClass) {
            throw new InvalidArgumentException('Missing related model.');
        }

        $form = $this->makeForm();

        $closure($form);

        $this->setAttribute('update_form', $form);

        return $this;
    }

    /**
     * Make relation form.
     *
     * @return RelationForm
     */
    protected function makeForm()
    {
        $form = new RelationForm($this->relatedModelClass, $this);

        $form->setRoutePrefix($this->formInstance->getRoutePrefix());

        $form->registered(function ($field) {
            $field->mergeOrSetAttribute('params', [
                'field_id'       => $this->id,
                'child_field_id' => $field->id,
            ]);
        });

        return $form;
    }

    /**
     * Add creation form.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function create(Closure $closure)
    {
        $form = $this->makeForm();

        $closure($form);

        // $this->form(function($form) use($closure) {

        // });
        $this->setAttribute('creation_form', $form);

        return $this;
    }

    /**
     * Add create and update form.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function createAndUpdateForm(Closure $closure)
    {
        $this->create($closure);
        $this->form($closure);

        return $this;
    }

    /**
     * Deletes the relation Model after unlinking the relation.
     *
     * @param  bool  $delete
     * @return $this
     */
    public function deleteUnlinked(bool $delete = true)
    {
        $this->setAttribute('delete_unlinked', $delete);

        $this->mergeOrSetAttribute('params', [
            'delete_unlinked' => $delete,
        ]);

        return $this;
    }

    /**
     * Option to hide the relation link.
     *
     * @param  bool  $hidden
     * @return $this
     */
    public function hideRelationLink(bool $hidden = true)
    {
        $this->setAttribute('hide_relation_link', $hidden);

        return $this;
    }
}
