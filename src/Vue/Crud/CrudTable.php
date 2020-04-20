<?php

namespace Fjord\Vue\Crud;

use Fjord\Vue\Table;

class CrudTable extends Table
{
    /**
     * Config instance.
     *
     * @var string
     */
    protected $config;

    /**
     * Create new CrudTable instance.
     *
     * @param Instance $config
     */
    public function __construct($config)
    {
        $this->config = $config;
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

        $route_prefix = $this->config->routePrefix();

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
