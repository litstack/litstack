<?php

namespace Ignite\Config;

use Ignite\Application\Navigation\Config;
use Ignite\Application\Navigation\NavigationConfigFactory;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\Config\Factories\CrudConfigFactory;
use Ignite\Crud\Config\Factories\CrudFormConfigFactory;
use Ignite\Crud\Config\Factories\CrudIndexConfigFactory;
use Ignite\Crud\Config\Traits\HasCrudIndex;
use Ignite\Crud\Config\Traits\HasCrudShow;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Config factories to be registered.
     *
     * @var array
     */
    protected $factories = [
        // Navigation
        Config::class => NavigationConfigFactory::class,

        // Crud
        CrudConfig::class   => CrudConfigFactory::class,
        HasCrudShow::class  => CrudFormConfigFactory::class,
        HasCrudIndex::class => CrudIndexConfigFactory::class,
    ];

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('lit.config', function ($app) {
            $loader = new ConfigLoader();

            foreach ($this->factories as $dependency => $factory) {
                $loader->factory($dependency, $factory);
            }

            return $loader;
        });

        (new RouterMacros)->register();
    }
}
