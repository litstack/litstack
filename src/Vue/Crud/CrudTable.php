<?php

namespace Fjord\Vue\Crud;

use Fjord\Vue\Table;

class CrudTable extends Table
{
    /**
     * Crud model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Create new CrudTable instance.
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Add table column to cols stack.
     *
     * @param string $label
     * @return \Fjord\Vue\Col
     */
    public function col(string $label = '')
    {
        $col = parent::col();

        $route_prefix = (new $this->model)->config()->route_prefix;

        // Default link.
        $col->link("{$route_prefix}/{id}/edit");

        return $col;
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param string $component
     * @return \Fjord\Vue\Crud\CrudTableComponent $col
     */
    public function component(string $component)
    {
        $col = new CrudTableComponent($component);

        $this->cols[] = $col;

        return $col;
    }
}
