<?php

namespace Fjord\Crud;

use Illuminate\Foundation\AliasLoader;
use Fjord\Support\Facades\Form as FormFacade;
use Fjord\Crud\Models\Relations\CrudRelations;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(CrudRelations::class);
        $this->app->register(RouteServiceProvider::class);
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

        /*
        $this->app->singleton('fjord.form', function () {
            return new FormFieldLoader;
        });

        $this->app->singleton('fjord.form.loader', function () {
            return new FormLoader();
        });
        */
    }
}
