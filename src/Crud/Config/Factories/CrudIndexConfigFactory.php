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

        if ($this->canCreate($config)) {
            $page->navigationRight()
                ->component('b-button')
                ->variant('primary')
                ->child($this->createButtonText($config))
                ->prop('href', lit()->url($config->routePrefix().'/create'));
        }

        $page->title($config->names['plural'] ?? '');

        $page->breadcrumb($this->getBreadcrumb($config, false));

        $method($page);

        return $page;
    }

    /**
     * Determines if a user can create a  new crud model.
     *
     * @param  ConfigHandler $config
     * @return bool
     */
    protected function canCreate(ConfigHandler $config)
    {
        if (! $user = lit_user()) {
            return false;
        }

        return $config->has('show')
            && $config->authorize(lit_user(), 'create');
    }

    /**
     * Get create button text.
     *
     * @param  ConfigHandler $config
     * @return string
     */
    protected function createButtonText(ConfigHandler $config)
    {
        return ucfirst(
            __lit('base.item_create', ['item' => $config->names['singular']])
        );
    }
}
