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

        $method($form);

        if (!empty($form->getCard())) {
            $form->card();
        }

        return $form;
    }
}
