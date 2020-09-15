<?php

namespace Ignite\Crud;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\Config\FormConfig;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Crud;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

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

        $this->mapCrudRoutes();
        $this->mapFormRoutes();
    }

    /**
     * Map crud routes.
     *
     * @return void
     */
    protected function mapCrudRoutes()
    {
        if (! lit()->installed()) {
            return;
        }

        // Todo: Cache this

        $files = File::allFiles(lit_config_path());

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (! Str::contains($file, '.php')) {
                continue;
            }

            $namespace = str_replace('/', '\\', 'Lit'.explode('lit/app', str_replace('.php', '', $file))[1]);
            $reflection = new ReflectionClass($namespace);

            if (! $reflection->getParentClass()) {
                continue;
            }

            if (! is_subclass_of($namespace, CrudConfig::class)) {
                continue;
            }

            $config = Config::getFromPath($file);

            if (! $config) {
                continue;
            }

            $this->app['lit.crud.router']->routes($config);
        }
    }

    /**
     * Map form routes.
     *
     * @return void
     */
    protected function mapFormRoutes()
    {
        $configPath = lit_config_path('Form');
        $directories = glob($configPath.'/*', GLOB_ONLYDIR);

        foreach ($directories as $formDirectory) {
            $configFiles = glob("{$formDirectory}/*.php");
            foreach ($configFiles as $path) {
                $configKey = Config::getKeyFromPath($path);

                $config = Config::get($configKey);
                if (! $config) {
                    continue;
                }

                if (! $config->getConfig() instanceof FormConfig) {
                    continue;
                }

                $this->app['lit.crud.router']->formRoutes($config);
                // Crud::formRoutes($config);
            }
        }
    }
}
