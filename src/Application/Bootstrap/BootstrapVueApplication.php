<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\AppComponent;
use Fjord\Application\Application;
use Fjord\Application\Vue\VueApplication;

class BootstrapVueApplication
{
    /**
     * Bootstrap VueApplication instance and bind it to
     * the Fjord application.
     *
     * @param  Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->singleton('vue.app', function () {
            return new AppComponent;
        });
    }
}
