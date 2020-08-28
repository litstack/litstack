<?php

namespace Ignite\Application;

use Ignite\Application\Commands\CastCommand;
use Ignite\Application\Commands\ComponentCommand;
use Ignite\Application\Commands\JobCommand;
use Ignite\Application\Commands\LivewireCommand;
use Ignite\Application\Commands\ProviderCommand;
use Ignite\Application\Commands\RequestCommand;
use Ignite\Application\Commands\ResourceCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Console\CastMakeCommand;
use Illuminate\Support\ServiceProvider;

class ArtisanServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'Livewire' => 'lit.command.livewire',
        'Cast'     => 'lit.command.cast',
        // 'ChannelMake'      => 'command.channel.make',
        'Component' => 'lit.command.component',
        // 'ConsoleMake'      => 'command.console.make',
        // 'ControllerMake'   => 'command.controller.make',
        // 'EventMake'        => 'command.event.make',
        // 'ExceptionMake'    => 'command.exception.make',
        // 'FactoryMake'      => 'command.factory.make',
        'Job' => 'lit.command.job',
        // 'ListenerMake'     => 'command.listener.make',
        // 'MailMake'         => 'command.mail.make',
        // 'MiddlewareMake'   => 'command.middleware.make',
        // 'ModelMake'        => 'command.model.make',
        // 'NotificationMake' => 'command.notification.make',
        // 'ObserverMake'     => 'command.observer.make',
        // 'PolicyMake'       => 'command.policy.make',
        'Provider' => 'lit.command.provider',
        'Request'  => 'lit.command.request',
        'Resource' => 'lit.command.resource',
        // 'RuleMake'         => 'command.rule.make',
        // 'SeederMake'       => 'command.seeder.make',
        // 'TestMake'         => 'command.test.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Register the given commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach ($this->devCommands as $command => $abstract) {
            call_user_func_array([$this, "register{$command}Command"], [$abstract]);
        }

        $this->commands(array_values($this->devCommands));
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerLivewireCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new LivewireCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerCastCommand($abstract)
    {
        // Skipping when CastMakeCommand doesnt exist. The command "make:cast" is
        // not available in older versions of laravel 7.
        if (! class_exists(CastMakeCommand::class)) {
            unset($this->devCommands['Cast']);

            return;
        }

        $this->app->singleton($abstract, function ($app) {
            return new CastCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerComponentCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ComponentCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerJobCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new JobCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerRequestCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RequestCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerProviderCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ProviderCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerResourceCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ResourceCommand($app['files']);
        });
    }
}
