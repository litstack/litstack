<?php

namespace FjordApp\Config\Form\Collections;

use Fjord\Crud\CrudForm;
use Fjord\Crud\Config\FormConfig;
use Fjord\Crud\Config\Traits\HasCrudForm;
use FjordApp\Controllers\Form\Collections\SettingsController;

class SettingsConfig extends FormConfig
{
    use HasCrudForm;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = SettingsController::class;

    /**
     * Form name, is used for routing.
     *
     * @var string
     */
    public $formName = 'settings';

    /**
     * Form singular name. This name will be displayed in the navigation.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Settings'
        ];
    }

    /**
     * Setup form.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return void
     */
    public function form(CrudForm $form)
    {
        $form->card(function ($form) {
            $form->input('title')
                ->title('Title')
                ->width(12)
                ->placeholder('Fjord');
        });
    }
}
