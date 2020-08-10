<?php

namespace Fjord\Translation;

use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register translator.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fjord.translator', function () {
            $translator = new Translator();

            $translator->addPath(
                fjord_path('resources/lang')
            );

            return $translator;
        });

        $this->app->bind(Translator::class, 'fjord.translator');
    }
}
