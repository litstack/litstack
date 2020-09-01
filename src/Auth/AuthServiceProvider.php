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
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
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
