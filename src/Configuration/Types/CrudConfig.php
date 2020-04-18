<?php

namespace Fjord\Configuration\Types;

use Fjord\Configuration\ConfigFile;
use Illuminate\Database\Eloquent\Builder;

abstract class CrudConfig extends ConfigFile
{
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
    protected function permissions()
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
    protected function routePrefix()
    {
        return 'crud/' . (new $this->model)->getTable();
    }

    /**
     * Get initial query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    protected function query(Builder $query)
    {
        return $query;
    }

    /**
     * Model singular and plural name.
     *
     * @return array $names
     */
    protected function names()
    {
        $tableName = (new $this->model)->getTable();

        return [
            'singular' => ucfirst(Str::singular($tableName)),
            'plural' => ucfirst($tableName),
        ];
    }
}
