<?php

namespace AwStudio\Fjord\Application\Bootstrap;

use AwStudio\Fjord\Application\Vue\VueApplication;
use AwStudio\Fjord\Application\Application;

class BootstrapVueApplication
{
    /**
     * Bootstrap VueApplication instance and bind it to 
     * the Fjord application.
     * 
     * @param AwStudio\Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->bind('vue', new VueApplication($app));
    }
}
