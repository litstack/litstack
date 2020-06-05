<?php

namespace Fjord\Crud;

use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Fjord\Support\Facades\Crud;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
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

        Route::macro('config', function ($config) {
            $this->action['config'] = $config;

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
            $crudName = Str::snake(str_replace('Config.php', '', str_replace($configPath . '/', '', $path)));
            $config = fjord()->config("crud.{$crudName}");

            Crud::routes($config);
        }
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
                $config = fjord()->config("form.{$collection}.{$formName}");

                Crud::formRoutes('form', $collection, $config->formName);
            }
        }
    }
}
