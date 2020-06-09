<?php

namespace Fjord\Crud;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Fjord\Support\Facades\Crud;
use Fjord\Crud\Config\CrudConfig;
use Illuminate\Support\Facades\File;
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

        $configPath = fjord_config_path();
        $configFiles = glob("{$configPath}/*.php");

        $files = File::allFiles(fjord_config_path());

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (!Str::contains($file, '.php')) {
                continue;
            }

            $namespace = str_replace("/", "\\", "FjordApp" . explode('fjord/app', str_replace('.php', '', $file))[1]);
            $reflection = new ReflectionClass($namespace);

            if (!$reflection->getParentClass()) {
                continue;
            }

            if ($reflection->getParentClass()->name != CrudConfig::class) {
                continue;
            }

            $configKey = collect(explode('/', str_replace('Config.php', '', str_replace($configPath . '/', '', $file))))
                ->map(fn ($item) => Str::snake($item))
                ->implode('.');

            Crud::routes(
                fjord()->config($configKey)
            );
        }

        foreach ($configFiles as $path) {
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
            $configFiles = glob("{$formDirectory}/*.php");;
            foreach ($configFiles as $path) {
                $configKey = collect(explode('/', str_replace('Config.php', '', str_replace(base_path('fjord/app/Config') . '/', '', $path))))
                    ->map(fn ($item) => Str::snake($item))
                    ->implode('.');

                Crud::formRoutes('form', fjord()->config($configKey));
            }
        }
    }
}
