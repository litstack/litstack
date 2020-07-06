<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Support\VueProp;
use Fjord\Vue\Crud\CrudTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class CrudIndexTable extends VueProp
{
    /**
     * Crud index table attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Index table.
     *
     * @var CrudIndexTable
     */
    protected $table;

    /**
     * Crud config.
     *
     * @var ConfigHandler
     */
    protected $config;

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
    protected $componentName = 'fj-crud-index-table';

    /**
     * Vue component instance.
     *
     * @var \Fjord\Vue\Component;
     */
    protected $component;

    /**
     * Create new CrudIndexTable instance.
     *
     * @param ConfigHandler $config
     */
    public function __construct($config)
    {
        $this->component = component($this->componentName)->prop('table', $this);
        $this->config = $config;
        $this->table = new CrudTable($config);
        $this->setDefaults();
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
        //$this->filter([]);
        $this->sortBy([
            'id.desc' => __f('fj.sort_new_to_old'),
            'id.asc'  => __f('fj.sort_old_to_new'),
        ]);
        $this->setAttribute('sortable', false);
        $this->addControl(
            component('fj-index-delete-all')
                ->routePrefix($this->config->route_prefix)
        );
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
     * Add table control.
     *
     * @param string|\Fjord\Vue\Component $control
     *
     * @return $this
     */
    public function addControl($control)
    {
        $controls = $this->getAttribute('controls');
        $controls[] = component($control)->toArray();
        $this->setAttribute('controls', $controls);

        return $this;
    }

    /**
     * Set table controls.
     *
     * @param array|Collection $controls
     *
     * @return $this
     */
    public function controls($controls)
    {
        foreach ($controls as $control) {
            $this->addControl($control);
        }

        return $this;
    }

    /**
     * Get index table instance.
     *
     * @return void
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Per page.
     *
     * @param int $perPage
     *
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
     * @param string $key
     *
     * @return $this
     */
    public function sortByDefault(string $key)
    {
        $this->setAttribute('sortByDefault', $key);

        return $this;
    }

    /**
     * Sortable CrudIndex table.
     *
     * @param bool $sortable
     *
     * @return $this
     */
    public function sortable(bool $sortable)
    {
        throw new \Exception('Sortable index tables comming soon.');
        $this->setAttribute('sortable', $sortable);

        return $this;
    }

    /**
     * Set search keys.
     *
     * @param array $keys
     *
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
     * @param array $sortBy
     *
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
     * @param array $filter
     *
     * @return $this
     */
    public function filter(array $filter)
    {
        $this->setAttribute('filter', $filter);

        return $this;
    }

    /**
     * Set query modifier.
     *
     * @param Closure $closure
     *
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
     * @param Builder $query
     *
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
     * Set attribute.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get attribute.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Render CrudIndexTable for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge($this->attributes, [
            'cols' => $this->table->toArray(),
        ]);
    }
}
