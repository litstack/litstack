<?php

namespace Ignite\Translation;

use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register litstack translator.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('lit.translator', function () {
            $translator = new Translator();

            $translator->addPath(
                lit_vendor_path('resources/lang')
            );

            return $translator;
        });

        $this->app->bind(Translator::class, 'lit.translator');
    }
}
