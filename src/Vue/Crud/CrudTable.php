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
     * Disable link on all table columns.
     *
     * @return $this
     */
    public function disableLink()
    {
        foreach ($this->cols as $col) {
            $col->link(false);
        }

        return $this;
    }

    /**
     * Add table column to cols stack.
     *
     * @param string $label
     *
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
     *
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
        if (! $this->config->has('show')) {
            return false;
        }

        $route_prefix = $this->config->routePrefix();

        // Default link.
        return "{$route_prefix}/{id}";
    }
}
