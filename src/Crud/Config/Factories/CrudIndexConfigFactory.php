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

        $deleteAll = component('fj-index-delete-all')
            ->prop('routePrefix', $config->routePrefix);

        $component->slot(
            'indexControls',
            $deleteAll
        );

        $method($component);

        return $component;
    }

    /**
     * Setup fj-crud-form component form.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return \Fjord\Vue\Component
     */
    public function formComponent(ConfigHandler $config, Closure $method)
    {
        $component = component('fj-crud-form');

        $method($component);

        return $component;
    }
}
