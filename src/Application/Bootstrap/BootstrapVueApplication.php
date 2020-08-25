<?php

namespace Ignite\Application\Bootstrap;

use Ignite\Application\AppComponent;
use Ignite\Application\Application;
use Ignite\Application\Vue\VueApplication;

class BootstrapVueApplication
{
    /**
     * Bootstrap VueApplication instance and bind it to
     * the Lit application.
     *
     * @param  Lit\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->singleton('vue.app', function () {
            return new AppComponent;
        });
    }
}
