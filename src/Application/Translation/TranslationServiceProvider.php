<?php

namespace Fjord\Application\Translation;

use Illuminate\Support\ServiceProvider;
use Fjord\Support\Facades\FjordLang;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        // Language path
        FjordLang::addPath(fjord_path('resources/lang'));
    }
}
