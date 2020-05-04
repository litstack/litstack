<?php

namespace Fjord\Crud\Config\Factories;

use Closure;
use Fjord\Crud\CrudForm;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;

class CrudFormConfigFactory extends ConfigFactory
{
    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return \Fjord\Crud\CrudForm
     */
    public function form(ConfigHandler $config, Closure $method)
    {
        $form = new CrudForm($config->model);

        $form->setRoutePrefix(
            strip_slashes($config->route_prefix . '/{id}')
        );

        $method($form);

        return $form;
    }

    /**
     * Setup crud-show component form.
     *
     * @param \Fjord\Config\ConfigHandler $config
     * @param Closure $method
     * @return \Fjord\Vue\Component
     */
    public function component(ConfigHandler $config, Closure $method)
    {
        $component = component('fj-crud-form');

        $method($component);

        return $component;
    }
}
