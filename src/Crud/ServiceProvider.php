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

        $this->app['fjord.app']->get('components')->register('fj-crud-index', [
            'props' => [
                'test' => [
                    'required' => true,
                    'type' => 'integer',

                ]
            ],
            'slots' => [
                'abc' => [
                    'required' => true
                ]
            ],
        ]);
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
            return new FormLoader;
        });

        $this->app['fjord.app']->singleton('crud', new Crud);
    }
}
