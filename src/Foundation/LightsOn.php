<?php

namespace Ignite\Foundation;

use Illuminate\Foundation\Application;

class LightsOn
{
    /**
     * Laravel application instance.
     *
     * @var Application
     */
    protected $laravel;

    /**
     * Litstack instance.
     *
     * @var Litstack
     */
    protected $liststack;

    /**
     * Create new LightsOn instance.
     *
     * @param  Application $app
     * @param  Litstack    $litstack
     * @return void
     */
    public function __construct(Application $laravel, Litstack $litstack)
    {
        $this->laravel = $laravel;
        $this->litstack = $litstack;
    }

    /**
     * Ignite the litstack.
     *
     * @return void
     */
    public function ignite()
    {
        if (! $this->litstack->installed()) {
            return;
        }

        $this->laravel->register(
            \Ignite\Application\ApplicationServiceProvider::class
        );

        $this->laravel->singleton('lit.app', function ($app) {
            $litstackApp = new \Ignite\Application\Application($app);

            // Bind litstack application.
            $this->litstack->bindApp($litstackApp);

            return $litstackApp;
        });

        $this->laravel->singleton(\Ignite\Application\Kernel::class, function ($app) {
            return new \Lit\Kernel($app->get('lit.app'));
        });

        // Initialize kernel singleton.
        $this->laravel[\Ignite\Application\Kernel::class];
    }
}
