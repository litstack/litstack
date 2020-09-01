<?php

namespace Ignite\Crud\Config\Factories;

use Closure;
use Ignite\Config\ConfigFactory;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\Actions\DestroyAction;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudShow;

class CrudFormConfigFactory extends ConfigFactory
{
    /**
     * Setup create and edit form.
     *
     * @param  \Ignite\Config\ConfigHandler $config
     * @param  Closure                      $method
     * @return \Ignite\Crud\CrudForm
     */
    public function show(ConfigHandler $config, Closure $method)
    {
        $form = new BaseForm($config->model);

        $form->setRoutePrefix(
            strip_slashes($config->route_prefix.'/{id}/api/show')
        );

        $page = new CrudShow($form);

        if ($config->instanceOf(CrudConfig::class)) {
            $page->navigationControls()->action(ucfirst(__lit('base.delete')), DestroyAction::class);
        }

        $page->navigationRight()->component('lit-crud-language');

        if ($config->has('index')) {
            $page->goBack($config->names['plural'], $config->route_prefix);
        }

        $page->title($config->names['singular'] ?? '');

        $method($page);

        return $page;
    }
}
