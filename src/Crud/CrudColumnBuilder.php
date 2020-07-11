<?php

namespace Fjord\Crud;

use Fjord\Config\ConfigHandler;
use Fjord\Contracts\Page\Column;
use Fjord\Page\Table\ColumnBuilder;

class CrudColumnBuilder extends ColumnBuilder
{
    /**
     * Crud config handler instance.
     *
     * @var ConfgHandler
     */
    protected $config;

    /**
     * Create new CrudColumnBuilder instance.
     *
     * @param ConfigHandler $config
     */
    public function __construct(ConfigHandler $config)
    {
        $this->config = $config;
    }

    /**
     * Add table column to cols stack.
     *
     * @param  string                       $label
     * @return \Fjord\Contracts\Page\Column
     */
    public function col($label = ''): Column
    {
        return parent::col($label)->link($this->defaultLink());
    }

    /**
     * Add table column to cols stack and set component.
     *
     * @param  string $component
     * @return mixed
     */
    public function component($component): Column
    {
        return parent::component($component)->link($this->defaultLink());
    }

    /**
     * Get default link.
     *
     * @return string|bool
     */
    public function defaultLink()
    {
        if (! $this->config->has('show')) {
            return false;
        }

        return $this->config->routePrefix().'/{id}';
    }

    /**
     * Get crud config handler instance.
     *
     * @return ConfigHandler
     */
    public function getConfig()
    {
        return $this->config;
    }
}
