<?php

namespace Lit\Application\Bootstrap;

use Lit\Application\AppComponent;
use Lit\Application\Application;
use Lit\Application\Vue\VueApplication;

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
