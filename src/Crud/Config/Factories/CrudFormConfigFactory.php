<?php

namespace Fjord\Crud\Config\Factories;

use Closure;
use Fjord\Config\ConfigFactory;
use Fjord\Config\ConfigHandler;
use Fjord\Crud\Actions\DestroyAction;
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

        $page->navigationControls()->action(ucfirst(__f('base.delete')), DestroyAction::class);

        $page->navigationRight()->component('fj-crud-language');

        $method($page);

        return $page;
    }
}
