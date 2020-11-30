<?php

namespace Ignite\Page;

use Ignite\Crud\Vue\WrapperComponent;
use Ignite\Page\Console\ActionCommand;
use Ignite\Page\Table\Components\ImageComponent;
use Ignite\Page\Table\Components\RelationComponent;
use Ignite\Page\Table\Components\ToggleComponent;
use Ignite\Page\Wrapper\CardWrapperComponent;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    /**
     * The Commands that should be registered.
     *
     * @var array
     */
    protected $commands = [
        'Action' => 'lit.command.action',
    ];

    /**
     * Vue components to be registered.
     *
     * @var array
     */
    protected $vueComponents = [
        'lit-col-image'         => ImageComponent::class,
        'lit-col-toggle'        => ToggleComponent::class,
        'lit-col-crud-relation' => RelationComponent::class,
        'lit-wrapper'           => WrapperComponent::class,
        'lit-wrapper-card'      => CardWrapperComponent::class,
    ];

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerVueComponents();
    }

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

        $this->registerFactory();
    }

    /**
     * Register page factory.
     *
     * @return void
     */
    protected function registerFactory()
    {
        $this->app->singleton('lit.page', function () {
            return new Factory;
        });
    }

    /**
     * Register vue components.
     *
     * @return void
     */
    protected function registerVueComponents()
    {
        $this->callAfterResolving('lit.vue', function ($vue) {
            foreach ($this->vueComponents as $name => $component) {
                $vue->component($name, $component);
            }
        });
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
    protected function registerActionCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ActionCommand($app['files'], $app['lit']);
        });
    }
}
