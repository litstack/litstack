<?php

namespace Fjord\Crud\Config\Factories;

use Closure;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;
use Fjord\Crud\CrudIndex;

class CrudIndexConfigFactory extends ConfigFactory
{
    /**
     * Setup index table.
     *
     * @param \Fjord\Config\Types\CrudConfig $config
     * @param Closure                        $method
     *
     * @return \Fjord\Vue\Crud\CrudTable
     */
    public function index(ConfigHandler $config, Closure $method)
    {
        $index = new CrudIndex($config);

        $method($index);

        return $index;
    }

    /**
     * Setup fj-crud-index component form.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure                     $method
     *
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
     * @param Closure                     $method
     *
     * @return \Fjord\Vue\Component
     */
    public function formComponent(ConfigHandler $config, Closure $method)
    {
        $component = component('fj-crud-form');

        $component->slot('headerControls', 'fj-crud-preview');

        $method($component);

        return $component;
    }
}
