<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use AwStudio\Fjord\Support\Facades\Fjord;
use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\Form\Controllers\MediaController;
use AwStudio\Fjord\Form\Controllers\FormController;
use AwStudio\Fjord\Form\Controllers\FormBlockController;
use AwStudio\Fjord\Form\Controllers\FormMorphOneController;
use AwStudio\Fjord\Form\Controllers\FormBelongsToManyController;
use AwStudio\Fjord\Form\Controllers\FormHasManyController;
use AwStudio\Fjord\Form\Controllers\FormRelationsController;
use AwStudio\Fjord\Support\Facades\Package;

class RouteServiceProvider extends LaravelRouteServiceProvider
{

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        if (!fjord()->installed()) {
            return;
        }

        $this->package = Package::get('aw-studio/fjord');

        $this->mapFormFieldRoutes();
        $this->mapFormRoutes();
        $this->mapCrudRoutes();
        $this->mapFormBlockRoutes();
        $this->mapFormRelationRoutes();
        $this->mapFormMorphOneRoutes();
        $this->mapFormBelongsToManyRoutes();
        $this->mapFormHasManyRoutes();
        $this->mapMediaRoutes();
    }

    protected function mapCrudRoutes()
    {
        if (!fjord()->installed()) {
            return;
        }

        $configFiles = $this->package->configFiles('crud');

        foreach ($configFiles as $name => $path) {
            $crud = last(explode('.', $name));
            $config = collect($this->package->rawConfig($name));

            if ($config->has('controller')) {
                $namespace = $config->controller;
            } else {
                $controller = Str::studly(Str::singular($crud)) . 'Controller';
                $namespace = "\\App\\Http\\Controllers\\Fjord\\{$controller}";
            }

            $this->package->route()->resource(config('fjord.route_prefix') . "/{$crud}", $namespace);

            $this->package->route()->post("/{$crud}/index", $namespace . "@postIndex")
                ->name("{$crud}.post_index");

            $this->package->route()->get("/{$crud}/{id}/relations/{relation}", $namespace . "@relationIndex");
            $this->package->route()->get("/{$crud}/{id}/relations/{relation}/create", $namespace . "@relationStore");
            $this->package->route()->delete("/{$crud}/{id}/relations/{relation}/{foreign_id}", $namespace . "@relationDestroy");
            $this->package->route()->post("/{$crud}/{id}/relations/{relation}/{foreign_id}/remove", $namespace . "@relationRemove");
            $this->package->route()->post("/unrelated-relation", $namespace . "@unrelatedRelation");
            $this->package->route()->post("/link-relation", $namespace . "@relationLink");

            FjordRoute::extensionRoutes($namespace);
        }
    }

    protected function mapFormRoutes()
    {
        $config = config('fjord.forms');
        $configPath = $this->package->getConfigPath('forms');
        $directories = glob($configPath . '/*', GLOB_ONLYDIR);

        foreach ($directories as $formDirectory) {
            $collection = str_replace("{$configPath}/", '', $formDirectory);
            $configFiles = $this->package->configFiles("forms.{$collection}");

            foreach ($configFiles as $configName => $path) {
                $formName = last(explode('.', $configName));
                $formRoutePrefix = $formName;
                $collectionRoutePrefix = $config['route_prefix'] ?? $collection;

                $this->package->route()->get("{$collectionRoutePrefix}/{$formRoutePrefix}", FormController::class . "@show")
                    ->name("form.{$collection}.$formName");
            }
        }
    }

    protected function mapFormFieldRoutes()
    {
        $this->package->route()->put('form_fields/{id}', FormController::class . "@update")->name('form_field.update');
    }

    protected function mapFormBlockRoutes()
    {
        $this->package->route()->post('form_blocks', FormBlockController::class . "@store")->name('form_block.store');
        $this->package->route()->put('form_blocks/{id}', FormBlockController::class . "@update")->name('form_block.update');
    }

    protected function mapFormRelationRoutes()
    {
        $this->package->route()->put('/relation', FormRelationsController::class . "@updateHasOne")->name('relation.update');
        $this->package->route()->post('/relations', FormRelationsController::class . "@index")->name('relations.index');
        $this->package->route()->put('/relations/order', FormRelationsController::class . "@order")->name('relations.order');
        $this->package->route()->post('/relations/store', FormRelationsController::class . "@store")->name('relation.store');
        $this->package->route()->post('/relations/delete', FormRelationsController::class . "@delete")->name('relation.delete');
    }

    protected function mapFormMorphOneRoutes()
    {
        $this->package->route()->post('/morph-one', FormMorphOneController::class . "@index")->name('morph_one.index');
        $this->package->route()->post('/morph-one/store', FormMorphOneController::class . "@store")->name('morph_one.store');
    }

    protected function mapFormBelongsToManyRoutes()
    {
        $this->package->route()->post('/belongs-to-many', FormBelongsToManyController::class . "@index");
        $this->package->route()->post('/belongs-to-many/relations', FormBelongsToManyController::class . "@relations");
        $this->package->route()->post('/belongs-to-many/update', FormBelongsToManyController::class . "@update");
    }

    protected function mapFormHasManyRoutes()
    {
        $this->package->route()->post('/has-many', FormHasManyController::class . "@index");
        $this->package->route()->post('/has-many/relations', FormHasManyController::class . "@relations");
        $this->package->route()->post('/has-many/update', FormHasManyController::class . "@update");
    }

    protected function mapMediaRoutes()
    {
        $this->package->route()->put('/media/attributes', MediaController::class . '@attributes')->name('media.attributes');
        $this->package->route()->post('/media', MediaController::class . '@store')->name('media.store');
        $this->package->route()->delete('/media/{medium}', MediaController::class . '@destroy')->name('media.destroy');
    }
}
