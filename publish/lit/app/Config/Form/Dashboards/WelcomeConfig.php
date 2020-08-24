<?php

namespace LitApp\Config\Form\Dashboards;

use Lit\Crud\Config\FormConfig;
use Lit\Crud\CrudShow;
use LitApp\Controllers\Form\Dashboards\WelcomeController;

class WelcomeConfig extends FormConfig
{
    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = WelcomeController::class;

    /**
     * Form route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return '/';
    }

    /**
     * Setup create and edit form.
     *
     * @param  \Lit\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->title('Welcome');
    }
}
