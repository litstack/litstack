<?php

namespace Lit\Crud\Config\Factories;

use Closure;
use Lit\Config\ConfigFactory;
use Lit\Config\ConfigHandler;
use Lit\Crud\CrudIndex;

class CrudIndexConfigFactory extends ConfigFactory
{
    /**
     * Setup index table.
     *
     * @param  \Lit\Config\Types\CrudConfig $config
     * @param  Closure                        $method
     * @return \Lit\Vue\Crud\CrudTable
     */
    public function index(ConfigHandler $config, Closure $method)
    {
        $page = new CrudIndex($config);

        if ($config->has('show')) {
            $page->navigationRight()->component('fj-crud-create-button');
        }

        $page->title($config->names['plural'] ?? '');

        $method($page);

        return $page;
    }
}
