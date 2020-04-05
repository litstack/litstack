<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\Vue\VueApplication;
use Fjord\Application\Application;

class BootstrapVueApplication
{
    /**
     * Bootstrap VueApplication instance and bind it to 
     * the Fjord application.
     * 
     * @param Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->bind('vue', new VueApplication($app));
    }
}
