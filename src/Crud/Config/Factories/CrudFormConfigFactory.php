<?php

namespace Lit\Crud\Config\Factories;

use Closure;
use Lit\Config\ConfigFactory;
use Lit\Config\ConfigHandler;
use Lit\Crud\Actions\DestroyAction;
use Lit\Crud\BaseForm;
use Lit\Crud\Config\CrudConfig;
use Lit\Crud\CrudShow;

class CrudFormConfigFactory extends ConfigFactory
{
    /**
     * Setup create and edit form.
     *
     * @param  \Lit\Config\ConfigHandler $config
     * @param  Closure                     $method
     * @return \Lit\Crud\CrudForm
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
