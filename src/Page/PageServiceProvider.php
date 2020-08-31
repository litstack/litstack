<?php

namespace Ignite\Page;

use Ignite\Page\Table\Components\ImageComponent;
use Ignite\Page\Table\Components\RelationComponent;
use Ignite\Page\Table\Components\ToggleComponent;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    /**
     * Vue components to be registered.
     *
     * @var array
     */
    protected $vueComponents = [
        'lit-col-image'         => ImageComponent::class,
        'lit-col-toggle'        => ToggleComponent::class,
        'lit-col-crud-relation' => RelationComponent::class,
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
