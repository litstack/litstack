<?php

namespace Fjord\Vue\Crud;

use Fjord\Vue\Table;
use Fjord\Vue\TableComponent;

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
        $col = parent::col($label);

        $col->link($this->defaultLink());

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
        $component = parent::component($component);

        $component->link($this->defaultLink());

        return $component;
    }

    public function defaultLink()
    {
        $route_prefix = $this->config->routePrefix();

        // Default link.
        return "{$route_prefix}/{id}/edit";
    }
}
