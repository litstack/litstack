<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Support\VueProp;
use Illuminate\Support\Arr;
use Fjord\Vue\Crud\CrudTable;

class CrudIndexTable extends VueProp
{
    protected $attributes = [];

    protected $table;

    protected $config;

    protected $queryModifier;

    protected $component = 'fj-crud-index-table';

    public function __construct($config)
    {
        $this->component = component($this->component)->prop('table', $this);
        $this->config = $config;
        $this->table = new CrudTable($config);
        $this->setDefaults();
    }

    public function setDefaults()
    {
        $this->sortByDefault('id.desc');
        $this->perPage(10);
        $this->search(['title']);
        $this->filter([]);
        $this->sortBy([
            'id.desc' => __f('fj.sort_new_to_old'),
            'id.asc' => __f('fj.sort_old_to_new'),
        ]);
        $this->setAttribute('sortable', false);
    }

    public function getComponent()
    {
        return $this->component;
    }

    public function width($width)
    {
        $this->component->prop('width', $width);

        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function perPage(int $perPage)
    {
        $this->setAttribute('perPage', $perPage);

        return $this;
    }

    public function sortByDefault(string $key)
    {
        $this->setAttribute('sortByDefault', $key);

        return $this;
    }

    public function sortable(bool $sortable)
    {
        throw new \Exception('Sortable index tables comming soon.');
        $this->setAttribute('sortable', $sortable);

        return $this;
    }

    public function search($keys)
    {
        $this->setAttribute('search', Arr::wrap($keys));

        return $this;
    }

    public function sortBy(array $sortBy)
    {
        $this->setAttribute('sortBy', $sortBy);

        return $this;
    }

    public function filter(array $filter)
    {
        $this->setAttribute('filter', $filter);

        return $this;
    }

    public function query(Closure $closure)
    {
        $this->queryModifier = $closure;

        return $this;
    }

    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        return $this->attribute['name'] ?? null;
    }

    public function getArray(): array
    {

        return array_merge($this->attributes, [
            'cols' => $this->table->toArray()
        ]);
    }
}
