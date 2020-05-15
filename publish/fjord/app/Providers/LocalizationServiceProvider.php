<?php

namespace FjordApp\Providers;

use Fjord\Support\Facades\FjordLang;
use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Paths for Fjord interface localization.
     *
     * @return array
     */
    protected function paths()
    {
        return [
            base_path('fjord/resources/lang/')
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
            FjordLang::addPath($path);
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
