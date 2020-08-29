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
     * Lit instance.
     *
     * @var Lit
     */
    protected $liststack;

    /**
     * Create new LightsOn instance.
     *
     * @param  Application $app
     * @param  Lit         $litstack
     * @return void
     */
    public function __construct(Application $laravel, Lit $litstack)
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
            return new \Ignite\Application\Application($app);
        });

        $this->laravel->singleton(\Lit\Kernel::class, function ($app) {
            return new \Lit\Kernel($app->get('lit.app'));
        });

        // Bind litstack application.
        $this->litstack->bindApp($this->app['lit.app']);

        // Initialize kernel singleton.
        $this->laravel[\Lit\Kernel::class];
    }
}
