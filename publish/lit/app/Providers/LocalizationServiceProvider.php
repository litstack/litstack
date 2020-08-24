<?php

namespace LitApp\Providers;

use Lit\Support\Facades\LitLang;
use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Paths for Lit interface localization.
     *
     * @return array
     */
    protected function paths()
    {
        return [
            base_path('lit/resources/lang/'),
        ];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addPaths();
    }

    /**
     * Add localization paths.
     *
     * @return void
     */
    protected function addPaths()
    {
        foreach ($this->paths() as $path) {
            LitLang::addPath($path);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
