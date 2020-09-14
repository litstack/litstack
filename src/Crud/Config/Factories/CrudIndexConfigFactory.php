<?php

namespace Ignite\Crud\Config\Factories;

use Closure;
use Ignite\Config\ConfigFactory;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\CrudIndex;

class CrudIndexConfigFactory extends ConfigFactory
{
    use Concerns\ManagesBreadcrumb;

    /**
     * Setup index table.
     *
     * @param  \Ignite\Config\Types\CrudConfig $config
     * @param  Closure                         $method
     * @return \Ignite\Vue\Crud\CrudTable
     */
    public function index(ConfigHandler $config, Closure $method)
    {
        $page = new CrudIndex($config);

        if ($config->has('show')) {
            $page->navigationRight()->component('lit-crud-create-button');
        }

        $page->title($config->names['plural'] ?? '');

        $page->breadcrumb($this->getBreadcrumb($config, false));

        $method($page);

        return $page;
    }
}
