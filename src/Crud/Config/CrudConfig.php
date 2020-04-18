<?php

namespace Fjord\Crud\Config;

use Fjord\Vue\CrudTable;
use Illuminate\Support\Str;
use Fjord\Support\Config as FjordConfig;
use Illuminate\Database\Eloquent\Builder;

abstract class CrudConfig extends FjordConfig
{
    use Traits\HasForm,
        Traits\HasIndex;

    /**
     * Controller class.
     *
     * @var string
     */
    protected $controller;

    /**
     * Model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Create new CrudConfig instance.
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Load permissions.
     *
     * @return array
     */
    public function permissions()
    {
        return [
            'create' => true,
            'read' => true,
            'update' => true,
            'delete' => true,
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string $route
     */
    public function route_prefix()
    {
        return 'crud/' . (new $this->model)->getTable();
    }

    /**
     * Prepare index query config.
     *
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public function prepareQuery()
    {
        return $this->model::query();
    }

    /**
     * Get initial query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public function query(Builder $query)
    {
        return $query;
    }

    /**
     * Prepare index query config.
     *
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    protected function prepareIndexQuery()
    {
        return clone $this->query;
    }

    /**
     * Model singular and plural name.
     *
     * @return array $names
     */
    public function names()
    {
        $tableName = (new $this->model)->getTable();

        return [
            'singular' => ucfirst(Str::singular($tableName)),
            'plural' => ucfirst($tableName),
        ];
    }
}
