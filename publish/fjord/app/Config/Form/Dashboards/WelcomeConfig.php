<?php

namespace FjordApp\Config\Form\Dashboards;

use Fjord\Crud\Config\FormConfig;
use Fjord\Crud\CrudShow;
use FjordApp\Controllers\Form\Dashboards\WelcomeController;

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
     * @param  \Fjord\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->title('Welcome');
    }
}
