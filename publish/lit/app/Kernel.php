<?php

namespace Lit;

use Ignite\Application\Kernel as LitstackKernel;
use Illuminate\Contracts\Auth\Access\Authorizable;

class Kernel extends LitstackKernel
{
    /**
     * Lit application service providers.
     *
     * @var array
     */
    public $providers = [
        Providers\LitstackServiceProvider::class,
        Providers\LivewireServiceProvider::class,
        Providers\LocalizationServiceProvider::class,
        Providers\RouteServiceProvider::class,
    ];

    /**
     * The litstack application's HTTP middleware stack.
     *
     * @var array
     */
    protected $middlewares = [
        //
    ];

    /**
     * Mount litstack application.
     *
     * @return void
     */
    public function mount()
    {
        $this->loadRepeatablesFrom(__DIR__.'/Repeatables');
    }

    /**
     * Determine if an authenticated user has access to the litstack application.
     *
     * @param  Authorizable $user
     * @return bool
     */
    public function authorize(Authorizable $user): bool
    {
        if (config('app.env') == 'production' && $user->email == 'admin@admin.com') {
            return false;
        }

        return true;
    }
}
