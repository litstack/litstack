<?php

namespace FjordApp\Config\Form\Pages;

use Fjord\Crud\Config\FormConfig;
use Fjord\Crud\CrudShow;
use FjordApp\Controllers\Form\Pages\HomeController;

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
     * @param \Fjord\Crud\CrudShow $page
     *
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('title')
                ->translatable()
                ->title('Title');

            $form->markdown(\Illuminate\Support\Facades\File::get(fjord_path('resources/docs/form-loader-example.md')));
        });
    }
}
