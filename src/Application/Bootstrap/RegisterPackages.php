<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\Application;
use Fjord\Support\Facades\Package;
use Illuminate\Console\Application as Artisan;
use Illuminate\Support\Facades\App;

class RegisterPackages
{
    /**
     * Registers artisan commands of all fjord packages.
     *
     * @param  \Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $this->app = $app;

        foreach (Package::all() as $name => $package) {
            $this->registerCommands($package);
            $this->registerProviders($package);
            $this->registerComponents($package);
        }
    }

    /**
     * Register package components.
     *
     * @param  mixed $package
     * @return void
     */
    public function registerComponents($package)
    {
        foreach ($package->components() as $name => $component) {
            $this->app->get('components')->register($name, $component);
        }
    }

    /**
     * Register package providers.
     *
     * @param  mixed $package
     * @return void
     */
    public function registerProviders($package)
    {
        foreach ($package->providers() as $provider) {
            app()->register($provider);
        }
    }

    /**
     * Register package commands.
     *
     * @param  mxied $package
     * @return void
     */
    public function registerCommands($package)
    {
        if (! App::runningInConsole()) {
            return;
        }

        Artisan::starting(function ($artisan) use ($package) {
            $artisan->resolveCommands($package->commands());
        });
    }
}
