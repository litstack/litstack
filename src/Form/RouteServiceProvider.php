<?php

namespace Fjord\Form;

use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Fjord\Support\Facades\FjordRoute;
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
                'permission' => "read {$crud}",
                'authorize' => function () use ($crud) {
                    $config = collect($this->package->rawConfig('crud' . $crud));
                    $controller = $config['controller'];
                }
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

        $this->mapFormRoutes();
        $this->mapCrudRoutes();
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

            $this->package->route()->put("/{$crud}/{id}/relation/{relation}/order", $namespace . "@orderRelation")
                ->name("crud.{$crud}.relation.order");
            $this->package->route()->delete("/{$crud}/{id}/relation/{relation}/{relation_id}", $namespace . "@deleteRelation")
                ->name("crud.{$crud}.relation.delete");
            $this->package->route()->post("/{$crud}/{id}/relation/{relation}/{relation_id}", $namespace . "@createRelation")
                ->name("crud.{$crud}.relation.create");

            $this->package->route()->post("/{$crud}/index", $namespace . "@postIndex")
                ->name("crud.{$crud}.post_index");

            $this->package->route()->put("/{$crud}/{id}/media/{media_id}", $namespace . '@updateMedia')
                ->name("crud.{$crud}.media.update");
            $this->package->route()->post("/{$crud}/{id}/media", $namespace . '@storeMedia')
                ->name("crud.{$crud}.media.store");
            $this->package->route()->delete("/{$crud}/{id}/media/{media_id}", $namespace . '@destroyMedia')
                ->name("crud.{$crud}.media.destroy");


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

                $this->package->route()->put("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/relation/{relation}/order", $config['controller'] . "@orderRelation")
                    ->name("form.{$collection}.{$formName}.relation.order");
                $this->package->route()->delete("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/relation/{relation}/{relation_id}",  $config['controller'] . "@deleteRelation")
                    ->name("form.{$collection}.{$formName}.relation.delete");
                $this->package->route()->post("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/relation/{relation}/{relation_id}", $config['controller'] . "@createRelation")
                    ->name("form.{$collection}.{$formName}.relation.store");

                $this->package->route()->put("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/media/{media_id}", $config['controller'] . '@updateMedia')
                    ->name("form.{$collection}.{$formName}.media.update");
                $this->package->route()->post("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/media", $config['controller'] . '@storeMedia')
                    ->name("form.{$collection}.{$formName}.media.store");
                $this->package->route()->delete("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}/media/{media_id}", $config['controller'] . '@destroyMedia')
                    ->name("form.{$collection}.{$formName}.media.destroy");

                $this->package->route()->put("{$collectionRoutePrefix}/{$formRoutePrefix}/{id}", $config['controller'] . "@update")->name('form_field.update');
            }
        }
    }
}
