<?php

namespace Ignite\Application\Providers;

use Ignite\Application\Console\CastCommand;
use Ignite\Application\Console\ComponentCommand;
use Ignite\Application\Console\ControllerCommand;
use Ignite\Application\Console\JobCommand;
use Ignite\Application\Console\LivewireCommand;
use Ignite\Application\Console\MiddlewareCommand;
use Ignite\Application\Console\NavCommand;
use Ignite\Application\Console\ProviderCommand;
use Ignite\Application\Console\RequestCommand;
use Ignite\Application\Console\ResourceCommand;
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
    protected $commands = [
        'Nav' => 'lit.command.nav',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $creatorCommands = [
        'Livewire' => 'lit.command.livewire',
        'Cast'     => 'lit.command.cast',
        // 'ChannelMake'      => 'lit.command.channel.make',
        'Component' => 'lit.command.component',
        // 'ConsoleMake'      => 'lit.command.console.make',
        'Controller' => 'lit.command.controller',
        // 'EventMake'        => 'lit.command.event.make',
        // 'ExceptionMake'    => 'lit.command.exception.make',
        // 'FactoryMake'      => 'lit.command.factory.make',
        'Job' => 'lit.command.job',
        // 'ListenerMake'     => 'lit.command.listener.make',
        // 'MailMake'         => 'lit.command.mail.make',
        'Middleware' => 'lit.command.middleware',
        // 'ModelMake'        => 'lit.command.model.make',
        // 'NotificationMake' => 'lit.command.notification.make',
        // 'ObserverMake'     => 'lit.command.observer.make',
        // 'PolicyMake'       => 'lit.command.policy.make',
        'Provider' => 'lit.command.provider',
        'Request'  => 'lit.command.request',
        'Resource' => 'lit.command.resource',
        // 'RuleMake'         => 'lit.command.rule.make',
        // 'SeederMake'       => 'lit.command.seeder.make',
        // 'TestMake'         => 'lit.command.test.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerCreatorCommands();
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCreatorCommands()
    {
        foreach ($this->creatorCommands as $command => $abstract) {
            call_user_func_array([$this, "register{$command}Command"], [$abstract]);
        }

        $this->commands(array_values($this->creatorCommands));
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
    protected function registerNavCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new NavCommand($app['lit.nav']);
        });
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
            unset($this->creatorCommands['Cast']);

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

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerMiddlewareCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new MiddlewareCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerControllerCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ControllerCommand($app['files'], $app['lit']);
        });
    }
}
