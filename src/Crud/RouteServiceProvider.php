<?php

namespace Ignite\Crud;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\Config\FormConfig;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Crud;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Support\Facades\Cache;
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
    }

    public function map()
    {
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

        foreach ($this->getCrudConfigs() as $config) {
            $this->app['lit.crud.router']->routes($config);
        }
    }

    /**
     * Get crud configs.
     *
     * @return void
     */
    protected function getCrudConfigs()
    {
        $configs = collect([]);
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
            $configs->push($config);
        }

        return $configs;
    }

    /**
     * Map form routes.
     *
     * @return void
     */
    protected function mapFormRoutes()
    {
        foreach ($this->getFormConfigs() as $config) {
            $this->app['lit.crud.router']->formRoutes($config);
        }
    }

    /**
     * Get form configs.
     *
     * @return void
     */
    public function getFormConfigs()
    {
        $configs = collect([]);
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

                $configs->push($config);
            }
        }

        return $configs;
    }
}
