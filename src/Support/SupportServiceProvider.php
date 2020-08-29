<?php

namespace Ignite\Support;

use Ignite\Support\Facades\Vue;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Vue components to be registered.
     *
     * @var array
     */
    protected $vueComponents = [
        'lit-info'  => InfoComponent::class,
        'lit-blade' => BladeComponent::class,
    ];

    /**
     * Macros to be registered.
     *
     * @var array
     */
    protected $macros = [
        Macros\BuilderSearch::class,
        Macros\FormMarkdown::class,
        Macros\BuilderSort::class,
        Macros\CrudMetaMacro::class,
        Macros\BladeBlock::class,
        Macros\RouteMatchesUri::class,
        Macros\ResponseMacros::class,
    ];

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMacros();

        $this->registerVueComponents();
    }

    /**
     * Register macros.
     *
     * @return void
     */
    protected function registerMacros()
    {
        foreach ($this->macros as $macro) {
            $instance = new $macro();

            if (method_exists($instance, 'register')) {
                $instance->register();
            }
        }
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
}
