<?php
namespace AwStudio\Fjord\Form;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use AwStudio\Fjord\Support\Facades\Fjord;
use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\Form\Controllers\MediaController;
use AwStudio\Fjord\Form\Controllers\FormController;
use AwStudio\Fjord\Form\Controllers\FormBlockController;
use AwStudio\Fjord\Form\Controllers\FormMorphOneController;
use AwStudio\Fjord\Form\Controllers\FormRelationsController;
use AwStudio\Fjord\Support\Facades\FormLoader;
use AwStudio\Fjord\Fjord\Controllers\RolePermissionController;

class RouteServiceProvider extends LaravelRouteServiceProvider
{

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        if(! fjord()->installed()) {
            return;
        }

        $this->mapFormFieldRoutes();
        $this->mapFormRoutes();
        $this->mapCrudRoutes();
        $this->mapFormBlockRoutes();
        $this->mapFormRelationRoutes();
        $this->mapFormMorphOneRoutes();
        $this->mapMediaRoutes();

    }

    protected function mapCrudRoutes()
    {
        foreach(Fjord::forms('crud') as $crud => $path) {
            $crudArray = require $path;

            if(array_key_exists('controller', $crudArray)){
                $namespace = $crudArray['controller'];
            }else{
                $controllerClass = Str::studly(Str::singular($crud)).'Controller';
                $namespace = "\\App\\Http\\Controllers\\Fjord\\{$controllerClass}";
            }

            FjordRoute::resource(config('fjord.route_prefix') . "/{$crud}", $namespace)
                ->except(['show']);
            FjordRoute::post("/{$crud}/index", $namespace . "@postIndex")
                ->name("{$crud}.post_index");

            FjordRoute::get("/{$crud}/{id}/relations/{relation}", $namespace . "@relationIndex");
            FjordRoute::get("/{$crud}/{id}/relations/{relation}/create", $namespace . "@relationStore");
            FjordRoute::delete("/{$crud}/{id}/relations/{relation}/{foreign_id}", $namespace . "@relationDestroy");
            FjordRoute::post("/{$crud}/{id}/relations/{relation}/{foreign_id}/remove", $namespace . "@relationRemove");

            FjordRoute::extensionRoutes($namespace);
        }
    }

    protected function mapFormRoutes()
    {
        $collections = config('fjord.forms');

        foreach($collections as $collectionName => $config) {

            $forms = Fjord::forms($collectionName);
            $collectionRoutePrefix = $config['route_prefix'] ?? $collectionName;

            foreach($forms as $formName => $path) {

                $formRoutePrefix = $formName;

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
        FjordRoute::put('/relations/order', FormRelationsController::class . "@order")->name('relations.order');
        FjordRoute::post('/relations/store', FormRelationsController::class . "@store")->name('relation.store');
        FjordRoute::post('/relations/delete', FormRelationsController::class . "@delete")->name('relation.delete');
    }

    protected function mapFormMorphOneRoutes()
    {
        FjordRoute::post('/morph-one', FormMorphOneController::class . "@index")->name('morph_one.index');
        FjordRoute::post('/morph-one/store', FormMorphOneController::class . "@store")->name('morph_one.store');
    }

    protected function mapMediaRoutes()
    {
        FjordRoute::put('/media/attributes', MediaController::class . '@attributes')->name('media.attributes');
        FjordRoute::post('/media', MediaController::class . '@store')->name('media.store');
        FjordRoute::delete('/media/{medium}', MediaController::class . '@destroy')->name('media.destroy');
    }

}
