<?php

namespace LitApp\Config\Form\Pages;

use Lit\Crud\Config\FormConfig;
use Lit\Crud\CrudShow;
use LitApp\Controllers\Form\Pages\HomeController;

class HomeConfig extends FormConfig
{
    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = HomeController::class;

    /**
     * Form route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'pages/home';
    }

    /**
     * Form singular name. This name will be displayed in the navigation.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Home',
        ];
    }

    /**
     * Setup create and edit form.
     *
     * @param \Lit\Crud\CrudShow $page
     *
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('title')->title('Title')->width(6);
        });
    }
}
