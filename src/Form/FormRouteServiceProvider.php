<?php
namespace AwStudio\Fjord\Form;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use AwStudio\Fjord\Support\Facades\Fjord;
use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\Form\Controllers\MediaController;
use AwStudio\Fjord\Form\Controllers\FormController;
use AwStudio\Fjord\Form\Controllers\FormBlockController;
use AwStudio\Fjord\Form\Controllers\FormRelationsController;

class FormRouteServiceProvider extends RouteServiceProvider
{

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapFormFieldRoutes();
        $this->mapFormRoutes();
        $this->mapCrudRoutes();
        $this->mapFormBlockRoutes();
        $this->mapFormRelationRoutes();
        $this->mapMediaRoutes();
    }

    protected function mapCrudRoutes()
    {
        foreach(Fjord::cruds() as $key => $crud) {
            $controllerClass = ucfirst(Str::camel(Str::singular($crud))) . "Controller";
            $namespace = "\\App\\Http\\Controllers\\Fjord\\{$controllerClass}";

            FjordRoute::resource(config('fjord.route_prefix') . "/{$crud}", $namespace)
                ->except(['show']);
        }
    }

    protected function mapFormRoutes()
    {
        $collections = config('fjord.forms');
        
        foreach($collections as $collectionName => $config) {

            $forms = Fjord::forms($collectionName);
            $collectionRoutePrefix = $config['route_prefix'] ?? $collectionName;

            foreach($forms as $formName => $path) {

                $form = require $path;
                $formRoutePrefix = $form['route_prefix'] ?? $formName;

                FjordRoute::get("{$collectionRoutePrefix}/{$formRoutePrefix}", FormController::class . "@show")
                    ->name("form.{$collectionName}.{$formName}");
            }
        }
    }

    protected function mapFormFieldRoutes()
    {
        FjordRoute::put('form_fields/{id}', FormController::class . "@update")->name('form_field.update');
    }

    protected function mapFormBlockRoutes()
    {
        FjordRoute::post('form_blocks', FormBlockController::class . "@store")->name('form_block.store');
        FjordRoute::put('form_blocks/{id}', FormBlockController::class . "@update")->name('form_block.update');
    }

    protected function mapFormRelationRoutes()
    {
        FjordRoute::put('/relation', FormRelationsController::class . "@updateHasOne")->name('relation.update');
        FjordRoute::post('/relations', FormRelationsController::class . "@index")->name('relations.index');
        FjordRoute::post('/relations/store', FormRelationsController::class . "@store")->name('relation.store');
        FjordRoute::delete('/relations/{index}', FormRelationsController::class . "@delete")->name('relation.delete');
    }

    protected function mapMediaRoutes()
    {
        FjordRoute::put('/media/attributes', MediaController::class . '@attributes')->name('media.attributes');
        FjordRoute::post('/media', MediaController::class . '@store')->name('media.store');
        FjordRoute::delete('/media/{medium}', MediaController::class . '@destroy')->name('media.destroy');
    }
}
