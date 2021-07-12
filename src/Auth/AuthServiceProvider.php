<?php

namespace Ignite\Auth;

use Ignite\Auth\Console\PermissionsCommand;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class AuthServiceProvider extends LaravelServiceProvider
{
    /**
     * The Commands that should be registered.
     *
     * @var array
     */
    protected $commands = [
        'Permissions' => 'lit.command.permissions',
    ];

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

        $this->app->singleton('lit.auth', function ($app) {
            return new Authentication($app['auth']);
        });

        if (lit()->installed()) {
            $this->app->register(RouteServiceProvider::class);
        }
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach ($this->commands as $command => $abstract) {
            call_user_func_array([$this, "register{$command}Command"], [$abstract]);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerPermissionsCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new PermissionsCommand($app['migrator'], $app['files']);
        });
    }
}
