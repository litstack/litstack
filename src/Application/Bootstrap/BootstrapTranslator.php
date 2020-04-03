<?php

namespace AwStudio\Fjord\Application\Bootstrap;

use AwStudio\Fjord\Application\Translation\Translator;
use AwStudio\Fjord\Application\Application;

class BootstrapTranslator
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
        $translator = new Translator($app);
        $app->bind('translator', $translator);
    }
}
