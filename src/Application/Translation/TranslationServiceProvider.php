<?php

namespace AwStudio\Fjord\Application\Translation;

use Illuminate\Support\ServiceProvider;
use AwStudio\Fjord\Support\Facades\FjordLang;

class TranslationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Language path
        FjordLang::addPath(fjord_path('resources/lang'));
    }
}
