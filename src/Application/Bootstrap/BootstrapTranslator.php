<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\Translation\Translator;
use Fjord\Application\Application;

class BootstrapTranslator
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
        $translator = new Translator($app);
        $app->bind('translator', $translator);
    }
}
