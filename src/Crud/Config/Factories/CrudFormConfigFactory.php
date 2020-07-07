<?php

namespace Fjord\Crud\Config\Factories;

use Closure;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;
use Fjord\Crud\BaseForm;
use Fjord\Crud\CrudShow;

class CrudFormConfigFactory extends ConfigFactory
{
    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure                     $method
     *
     * @return \Fjord\Crud\CrudForm
     */
    public function show(ConfigHandler $config, Closure $method)
    {
        $form = new BaseForm($config->model);

        $form->setRoutePrefix(
            strip_slashes($config->route_prefix.'/{id}/api/show')
        );

        $page = new CrudShow($form);

        $page->navigationRight()->component('fj-crud-language');

        $method($page);

        return $page;
    }

    /**
     * Setup crud-show component form.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure                     $method
     *
     * @return \Fjord\Vue\Component
     */
    public function component(ConfigHandler $config, Closure $method)
    {
        $component = component('fj-crud-form');

        $component->slot('headerControls', 'fj-crud-preview');

        $method($component);

        return $component;
    }
}
