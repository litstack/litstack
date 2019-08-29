<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use AwStudio\Fjord\Support\Facades\Form as FormFacade;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register('AwStudio\Fjord\Form\FormRouteServiceProvider');
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
