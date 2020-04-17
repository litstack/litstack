<?php

namespace Fjord\Crud;

use Illuminate\Routing\Router;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Support\Facades\Package;
use Illuminate\Support\Facades\Route;
use Fjord\Crud\Requests\Traits\AuthorizeController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    use AuthorizeController;

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->package = Package::get('aw-studio/fjord');

        $this->mapFormRoutes();
        $this->mapCrudRoutes();
    }

    protected function mapCrudRoutes()
    {
        if (!fjord()->installed()) {
            return;
        }
        $configPath = fjord_config_path('Crud');
        $configFiles = glob("{$configPath}/*.php");;

        foreach ($configFiles as $path) {
            $crudName = strtolower(str_replace('Config.php', '', str_replace($configPath . '/', '', $path)));
            $model = "App\\Models\\{$crudName}";
            $config = $model::config();
            $controller = $config->controller;
            $self = $this;


            $this->package->route()->group(function () use ($config, $controller, $self, $model) {
                $table = (new $model)->getTable();
                Route::group([
                    'prefix' => $config->route_prefix,
                    'as' => "crud.{$table}.",
                ], function () use ($config, $controller, $self, $table) {
                    $type = 'crud';
                    require fjord_path('src/Crud/routes.php');

                    $self->addCrudNavPreset($table, $config, app()->get('router'));
                });
            });
        }
    }

    /**
     * Register form navigation preset.
     *
     * @param string $name
     * @param FormConfig $config
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function addCrudNavPreset(string $name, $config, Router $router)
    {
        $groupStack = last($router->getGroupStack());
        $link = '/' . $groupStack['prefix'] . '/';

        $this->package->addNavPreset("crud.{$name}", [
            'link' => $link,
            'title' => ucfirst($config->names['plural']),
            'authorize' => function (FjordUser $user) use ($config) {
                return $this->authorizeController(
                    app()->get('request'),
                    'read',
                    $config->controller
                );
            }
        ]);
    }

    /**
     * Map form routes.
     *
     * @return void
     */
    protected function mapFormRoutes()
    {
        $configPath = fjord_config_path('Form');
        $directories = glob($configPath . '/*', GLOB_ONLYDIR);
        //dd($configPath, $directories);

        foreach ($directories as $formDirectory) {
            $collection = strtolower(str_replace("{$configPath}/", '', $formDirectory));
            $configFiles = glob("{$formDirectory}/*.php");;
            foreach ($configFiles as $path) {
                $formName = strtolower(str_replace('Config.php', '', str_replace($formDirectory . '/', '', $path)));
                $config = fjord()->config("form.{$collection}.{$formName}");

                $controller = $config->controller;
                $self = $this;

                $this->package->route()->group(function () use ($config, $controller, $self) {
                    Route::group([
                        'prefix' => $config->route_prefix,
                        'as' => "form.{$config->collection}.{$config->formName}.",
                    ], function () use ($config, $controller, $self) {
                        $type = 'form';
                        require fjord_path('src/Crud/routes.php');

                        $self->addFormNavPreset($config, app()->get('router'));
                    });
                });
            }
        }
    }

    /**
     * Register form navigation preset.
     *
     * @param FormConfig $config
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function addFormNavPreset($config, Router $router)
    {
        $groupStack = last($router->getGroupStack());
        $link = '/' . $groupStack['prefix'] . '/';


        $this->package->addNavPreset("{$config->collection}.{$config->formName}", [
            'link' => $link,
            'title' => ucfirst($config->formName),
            'authorize' => function (FjordUser $user) use ($config) {
                return $this->authorizeController(
                    app()->get('request'),
                    'read',
                    $config->controller
                );
            }
        ]);
    }
}
