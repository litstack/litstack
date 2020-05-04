<?php

namespace Fjord\Crud\Config\Factories;

use Closure;
use Fjord\Vue\Crud\CrudTable;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;

class CrudIndexConfigFactory extends ConfigFactory
{
    /**
     * Setup index table.
     *
     * @param \Fjord\Config\Types\CrudConfig $config
     * @param Closure $method
     * @return \Fjord\Vue\Crud\CrudTable
     */
    public function index(ConfigHandler $config, Closure $method)
    {
        $table = new CrudTable($config);

        $method($table);

        return $table;
    }

    /**
     * Setup fj-crud-index component form.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return \Fjord\Vue\Component
     */
    public function indexComponent(ConfigHandler $config, Closure $method)
    {
        $component = component('fj-crud-index');

        $method($component);

        return $component;
    }
}
