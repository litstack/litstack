<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\Application;

class RegisterSingletons
{
    /**
     * Bind singletons to app.
     *
     * @param Fjord\Application\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        foreach ($app->singletons() as $key => $class) {
            $app->bind($key, new $class($app));
        }
    }
}
