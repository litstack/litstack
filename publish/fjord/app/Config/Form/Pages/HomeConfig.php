<?php

namespace FjordApp\Config\Form\Pages;

use Fjord\Crud\CrudForm;
use App\Models\Department;
use Fjord\Crud\Config\FormConfig;
use Fjord\Vue\Crud\RelationTable;
use Fjord\Crud\Config\Traits\HasCrudForm;
use Fjord\Crud\Fields\Blocks\Repeatables;
use FjordApp\Controllers\Form\Pages\HomeController;

class HomeConfig extends FormConfig
{
    use HasCrudForm;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = HomeController::class;

    /**
     * Form name, is used for routing.
     *
     * @var string
     */
    public $formName = 'home';

    /**
     * Form singular name. This name will be displayed in the navigation.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Home'
        ];
    }

    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return void
     */
    public function form(CrudForm $form)
    {
        $form->card(function ($form) {

            $form->input('title')
                ->title('Title');

            $form->markdown(\Illuminate\Support\Facades\File::get(fjord_path('resources/docs/form-loader-example.md')));
        });
    }
}
