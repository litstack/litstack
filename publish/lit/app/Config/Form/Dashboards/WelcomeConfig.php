<?php

namespace Lit\Config\Form\Dashboards;

use Ignite\Crud\Config\FormConfig;
use Ignite\Crud\CrudShow;
use Lit\Http\Controllers\Form\Dashboards\WelcomeController;

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
     * Welcome title.
     *
     * @return string
     */
    public function title()
    {
        return __lit('base.user_welcome', ['user' => lit_user()->first_name]);
    }

    /**
     * Setup create and edit form.
     *
     * @param  \Ignite\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->view('lit::welcome');
    }
}
