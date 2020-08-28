<?php

namespace Lit\Providers;

use Ignite\Support\LoadsLivewireComponents;
use Illuminate\Support\ServiceProvider;

class LivewireServiceProvider extends ServiceProvider
{
    use LoadsLivewireComponents;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadLivewireComponentsFrom(
            'lit/app/Http/Livewire',
            'Lit\\Http\\Livewire',
            'lit'
        );
    }
}
