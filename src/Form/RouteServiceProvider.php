<?php

namespace Fjord\Form;

use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Fjord\Support\Facades\Fjord;
use Fjord\Support\Facades\FjordRoute;
use Fjord\Form\Controllers\MediaController;
use Fjord\Form\Controllers\FormController;
use Fjord\Form\Controllers\FormBlockController;
use Fjord\Form\Controllers\FormMorphOneController;
use Fjord\Form\Controllers\FormBelongsToManyController;
use Fjord\Form\Controllers\FormHasManyController;
use Fjord\Form\Controllers\FormRelationsController;
use Fjord\Support\Facades\Package;

class RouteServiceProvider extends LaravelRouteServiceProvider
{

    public function boot()
    {
        parent::boot();
        $provider = $this;
        $this->app->booted(function () use ($provider) {
            $provider->addNavPresets();
        });
    }

    public function addNavPresets()
    {
        $configFiles = $this->package->configFiles('crud');

        foreach ($configFiles as $name => $path) {
            $crud = last(explode('.', $name));
            $this->package->addNavPreset("crud.{$crud}", [
                'link' => route("fjord.aw-studio.fjord.crud.{$crud}.index"),
                'title' => ucfirst($crud),
                'permission' => "read {$crud}"
            ]);
        }

        $configPath = $this->package->getConfigPath('forms');
        $directories = glob($configPath . '/*', GLOB_ONLYDIR);

        foreach ($directories as $formDirectory) {
            $collection = str_replace("{$configPath}/", '', $formDirectory);
            $configFiles = $this->package->configFiles("forms.{$collection}");

            foreach ($configFiles as $configName => $path) {
                $formName = last(explode('.', $configName));

                $this->package->addNavPreset("{$collection}.{$formName}", [
                    'link' => route("fjord.aw-studio.fjord.form.{$collection}.{$formName}"),
                    'title' => ucfirst($formName),
                    //'permission' => "read {$crud}"
                ]);
            }
        }
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
                $namespace = $config['controller'];
            } else {
                $controller = Str::studly(Str::singular($crud)) . 'Controller';
                $namespace = "\\App\\Http\\Controllers\\Fjord\\Crud\\{$controller}";
            }

            $this->package->route()->get("/{$crud}/all", $namespace . "@all");
            $this->package->route()->as($this->package->getRouteAs() . 'crud')->resource(config('fjord.route_prefix') . "/{$crud}", $namespace);

            $this->package->route()->post("{$crud}/{id}/blocks", $config['controller'] . "@storeBlock")
                ->name("crud.{$crud}.blocks.store");
            $this->package->route()->put("{$crud}/{id}/blocks/{block_id}", $config['controller'] . "@updateBlock")
                ->name("crud.{$crud}.blocks.update");
            $this->package->route()->delete("{$crud}/{id}/blocks/{block_id}", $config['controller'] . "@destroyBlock")
                ->name("crud.{$crud}.blocks.destroy");

            $this->package->route()->delete("/{$crud}/{id}/relation/{relation}/{relation_id}", $namespace . "@deleteRelation")
                ->name("crud.{$crud}.relation.delete");
            $this->package->route()->post("/{$crud}/{id}/relation/{relation}/{relation_id}", $namespace . "@createRelation")
                ->name("crud.{$crud}.relation.create");

            $this->package->route()->post("/{$crud}/index", $namespace . "@postIndex")
                ->name("crud.{$crud}.post_index");

            /*
            $this->package->route()->get("/{$crud}/{id}/relations/{relation}", $namespace . "@relationIndex");
            $this->package->route()->get("/{$crud}/{id}/relations/{relation}/create", $namespace . "@relationStore");
            $this->package->route()->delete("/{$crud}/{id}/relations/{relation}/{foreign_id}", $namespace . "@relationDestroy");
            $this->package->route()->post("/{$crud}/{id}/relations/{relation}/{foreign_id}/remove", $namespace . "@relationRemove");
            $this->package->route()->post("/unrelated-relation", $namespace . "@unrelatedRelation");
            $this->package->route()->post("/link-relation", $namespace . "@relationLink");
            */

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
                $config = $this->package->rawConfig($configName);
                $formName = last(explode('.', $configName));
                $formRoutePrefix = $formName;
                $collectionRoutePrefix = $config['route_prefix'] ?? $collection;

                $this->package->route()->get("{$collectionRoutePrefix}/{$formRoutePrefix}", $config['controller'] . "@show")
                    ->name("form.{$collection}.{$formName}");

                $this->package->route()->post("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/blocks", $config['controller'] . "@storeBlock")
                    ->name("form.{$collection}.{$formName}.blocks.store");
                $this->package->route()->put("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/blocks/{block_id}", $config['controller'] . "@updateBlock")
                    ->name("form.{$collection}.{$formName}.blocks.update");
                $this->package->route()->delete("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/blocks/{block_id}", $config['controller'] . "@destroyBlock")
                    ->name("form.{$collection}.{$formName}.blocks.destroy");

                $this->package->route()->delete("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/relation/{relation}/{relation_id}",  $config['controller'] . "@deleteRelation")
                    ->name("form_fields.relation.delete");
                $this->package->route()->post("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/relation/{relation}/{relation_id}", $config['controller'] . "@createRelation")
                    ->name("form_fields.relation.store");

                $this->package->route()->put("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}", $config['controller'] . "@update")->name('form_field.update');
            }
        }
    }

    protected function mapFormFieldRoutes()
    {
    }

    protected function mapFormBlockRoutes()
    {
        $this->package->route()->post('form_blocks', FormBlockController::class . "@store")->name('form_block.store');
        $this->package->route()->put('form_blocks/{id}', FormBlockController::class . "@update")->name('form_block.update');
    }

    protected function mapMediaRoutes()
    {
        $this->package->route()->put('/media/attributes', MediaController::class . '@attributes')->name('media.attributes');
        $this->package->route()->post('/media', MediaController::class . '@store')->name('media.store');
        $this->package->route()->delete('/media/{medium}', MediaController::class . '@destroy')->name('media.destroy');
    }
}
