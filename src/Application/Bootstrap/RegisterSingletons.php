<?php

namespace Ignite\Application\Bootstrap;

use Ignite\Application\Application;

class RegisterSingletons
{
    /**
     * Bind singletons to app.
     *
     * @param  Lit\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        foreach ($app->singletons() as $abstract => $class) {
            app()->singleton($app->getAbstract($abstract), function () use ($class) {
                return app()->make($class);
            });
        }
    }
}
