<?php

namespace Fjord\Form;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use Fjord\Support\Facades\Form as FormFacade;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register('Fjord\Form\RouteServiceProvider');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Form', FormFacade::class);

        $this->app->singleton('fjord.form', function () {
            return new Form();
        });

        $this->app->singleton('fjord.form.loader', function () {
            return new FormLoader();
        });
    }
}
