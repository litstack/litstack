<?php

namespace FjordApp\Config\Form\Dashboards;

use Fjord\Crud\Config\FormConfig;
use Fjord\Crud\CrudShow;
use FjordApp\Controllers\Form\Dashboards\MainDashboardController;

class MainDashboardConfig extends FormConfig
{
    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = MainDashboardController::class;

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
     * Form singular name. This name will be displayed in the navigation.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Dashboard',
        ];
    }

    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Crud\CrudShow $page
     *
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->info()->text('<a href="https://www.fjord-admin.com/docs/charts/basics/">Read to docs to learn how to create charts for your dashboard in no time.</a>');
        });
    }
}
