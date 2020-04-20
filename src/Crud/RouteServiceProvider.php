<?php

namespace Fjord\Crud;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Support\Facades\Package;
use Fjord\Crud\Requests\Traits\AuthorizeController;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    use AuthorizeController;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->macros();
    }

    /**
     * Register macros.
     *
     * @return void
     */
    public function macros()
    {
        RouteFacade::macro('config', function ($class) {
            $this->config = $class;

            if (isset($this->groupStack[0])) {
                $this->groupStack[0]['config'] = $this->config;
            }
            return $this;
        });

        Route::macro('getConfig', function () {
            $key = $this->action['config'] ?? null;
            if (!$key) {
                return;
            }
            return fjord()->config($key);
        });
    }

    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        $this->package = Package::get('aw-studio/fjord');

        $this->mapFormRoutes();
        $this->mapCrudRoutes();
    }

    /**
     * Map crud routes.
     *
     * @return void
     */
    protected function mapCrudRoutes()
    {
        if (!fjord()->installed()) {
            return;
        }
        $configPath = fjord_config_path('Crud');
        $configFiles = glob("{$configPath}/*.php");

        foreach ($configFiles as $path) {
            $crudName = strtolower(str_replace('Config.php', '', str_replace($configPath . '/', '', $path)));
            $configKey = "crud.{$crudName}";
            $config = fjord()->config($configKey);
            $controller = $config->controller;
            $self = $this;

            $this->package->route()->group(function () use ($config, $configKey, $controller, $self, $crudName) {
                $tableName = (new $config->model)->getTable();
                RouteFacade::group([
                    'config' => $configKey,
                    'prefix' => $config->route_prefix,
                    'as' => "crud.{$tableName}",
                ], function () use ($config, $controller, $self, $tableName) {
                    $type = 'crud';
                    require fjord_path('src/Crud/routes.php');

                    $self->addCrudNavPreset($tableName, $config, app()->get('router'));
                });
            });
        }
    }

    /**
     * Register crud navigation preset.
     *
     * @param string $name
     * @param FormConfig $config
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function addCrudNavPreset(string $name, $config, Router $router)
    {
        $groupStack = last($router->getGroupStack());
        $link = '/' . $groupStack['prefix'];

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

        foreach ($directories as $formDirectory) {
            $collection = strtolower(str_replace("{$configPath}/", '', $formDirectory));
            $configFiles = glob("{$formDirectory}/*.php");;
            foreach ($configFiles as $path) {
                $formName = strtolower(str_replace('Config.php', '', str_replace($formDirectory . '/', '', $path)));
                $configKey = "form.{$collection}.{$formName}";
                $config = fjord()->config($configKey);

                $controller = $config->controller;
                $self = $this;

                $this->package->route()->group(function () use ($config, $configKey, $controller, $self) {
                    RouteFacade::group([
                        'prefix' => $config->route_prefix,
                        'as' => $configKey . ".",
                        'config' => $configKey
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
        $link = '/' . $groupStack['prefix'];


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
