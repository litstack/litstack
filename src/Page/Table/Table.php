<?php

namespace Fjord\Page\Table;

use Closure;
use Fjord\Exceptions\Traceable\MissingAttributeException;
use Fjord\Support\HasAttributes;
use Fjord\Support\VueProp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Table extends VueProp
{
    use HasAttributes;

    /**
     * Table column builder.
     *
     * @var ColumnBuilder
     */
    protected $builder;

    /**
     * Table route prefix.
     *
     * @var string
     */
    protected $routePrefix;

    /**
     * Table model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Table actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Query modifier for eager loads and selections.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $queryModifier;

    /**
     * Vue component name.
     *
     * @var string
     */
    protected $componentName = 'fj-page-table';

    /**
     * Vue component instance.
     *
     * @var \Fjord\Vue\Component
     */
    protected $component;

    /**
     * Create new CrudIndexTable instance.
     *
     * @param string        $routePrefix
     * @param ColumnBuilder $builder
     */
    public function __construct($routePrefix, ColumnBuilder $builder)
    {
        $this->routePrefix($routePrefix);
        $this->builder = $builder;
        $this->component = component($this->componentName)
            ->prop('table', $this);

        $this->setDefaults();
    }

    /**
     * Set table route prefix.
     *
     * @param  string $routePrefix
     * @return $this
     */
    public function routePrefix(string $routePrefix)
    {
        $this->setAttribute('route_prefix', $routePrefix);

        return $this;
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    public function setDefaults()
    {
        $this->setAttribute('controls', collect([]));
        $this->sortByDefault('id.desc');
        $this->perPage(10);
        $this->search(['title']);
        $this->sortBy([
            'id.desc' => __f('fj.sort_new_to_old'),
            'id.asc'  => __f('fj.sort_old_to_new'),
        ]);
    }

    /**
     * Set singular name.
     *
     * @param  string $name
     * @return $this
     */
    public function singularName($name)
    {
        $this->setAttribute('singularName', $name);

        return $this;
    }

    /**
     * Set plural name.
     *
     * @param  string $name
     * @return void
     */
    public function pluralName($name)
    {
        $this->setAttribute('pluralName', $name);

        return $this;
    }

    /**
     * Get component instance.
     *
     * @return void
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * Table card width.
     *
     * @param int|float $width
     *
     * @return $this
     */
    public function width($width)
    {
        $this->component->prop('width', $width);

        return $this;
    }

    /**
     * Add an action.
     *
     * @param  string                     $title
     * @param  array|string|callable|null $action
     * @return $this
     */
    public function action($title, $action)
    {
        $id = count($this->actions);
        $this->actions[$id] = $action = new TableAction($title, $action);

        $action->route($this->getActionRoutePrefix()."{$id}");
        $controls = $this->getAttribute('controls');
        $controls[] = $action->getComponent()->toArray();

        return $this;
    }

    /**
     * Get action route prefix.
     *
     * @return string
     */
    protected function getActionRoutePrefix()
    {
        return $this->getAttribute('route_prefix').'/run-action/';
    }

    /**
     * Get action by id.
     *
     * @param  string $key
     * @return mixed
     */
    public function getAction($id)
    {
        return $this->actions[$id];
    }

    /**
     * Get builder.
     *
     * @return ColumnBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Per page.
     *
     * @param  int   $perPage
     * @return $this
     */
    public function perPage(int $perPage)
    {
        $this->setAttribute('perPage', $perPage);

        return $this;
    }

    /**
     * Set sort by default.
     *
     * @param  string $key
     * @return $this
     */
    public function sortByDefault(string $key)
    {
        $this->setAttribute('sortByDefault', $key);

        return $this;
    }

    /**
     * Set search keys.
     *
     * @param  array $keys
     * @return void
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
     * Set sortBy keys.
     *
     * @param  array $sortBy
     * @return void
     */
    public function sortBy(array $keys)
    {
        $this->setAttribute('sortBy', $keys);

        return $this;
    }

    /**
     * Set table filters.
     *
     * @param  array $filter
     * @return $this
     */
    public function filter(array $filter)
    {
        $this->setAttribute('filter', $filter);

        return $this;
    }

    /**
     * Set table model.
     *
     * @param  string $model
     * @return $this
     */
    public function model(string $model)
    {
        $this->model = $model;

        if (! $this->hasAttribute('singularName')) {
            $this->singularName(class_basename($model));
        }

        if (! $this->hasAttribute('pluralName')) {
            $this->pluralName(Str::plural(class_basename($model)));
        }

        return $this;
    }

    /**
     * Set query modifier.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function query(Closure $closure)
    {
        $this->queryModifier = $closure;

        return $this;
    }

    /**
     * Get modified query.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function getQuery(Builder $query)
    {
        if (! $this->queryModifier) {
            return $query;
        }

        $modifier = $this->queryModifier;
        $modifier($query);

        return $query;
    }

    /**
     * Render CrudIndexTable for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        if (! $this->model) {
            throw new MissingAttributeException('Missing attribute [model] for '.static::class);
        }

        return array_merge($this->attributes, [
            'cols' => $this->builder,
        ]);
    }
}
