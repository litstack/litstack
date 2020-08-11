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
     * @param  \Fjord\Config\Types\CrudConfig $config
     * @param  Closure                        $method
     * @return \Fjord\Vue\Crud\CrudTable
     */
    public function index(ConfigHandler $config, Closure $method)
    {
        $page = new CrudIndex($config);

        if ($config->has('show')) {
            $page->navigationRight()->component('fj-crud-create-button');
        }

        $method($page);

        return $page;
    }
}
