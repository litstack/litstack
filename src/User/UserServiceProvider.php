<?php

namespace Ignite\User;

use Ignite\User\Console\AdminCommand;
use Ignite\User\Console\UserCommand;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class UserServiceProvider extends LaravelServiceProvider
{
    /**
     * The Commands that should be registered.
     *
     * @var array
     */
    protected $commands = [
        'Admin' => 'lit.command.admin',
        'User'  => 'lit.command.user',
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
    protected function registerAdminCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new AdminCommand();
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerUserCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new UserCommand();
        });
    }
}
