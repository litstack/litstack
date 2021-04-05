<?php

namespace Ignite\Search;

use Ignite\Config\ConfigLoader;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->callAfterResolving('lit.config', function (ConfigLoader $loader) {
            $loader->factory(SearchConfig::class, SearchConfigFactory::class);
        });

        $this->app->register(SearchRouteServiceProvider::class);
    }
}
