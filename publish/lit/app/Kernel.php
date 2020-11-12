<?php

namespace Lit;

use Ignite\Application\Kernel as LitstackKernel;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;

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
     * Middlewares that are used by litstack routes for authenticated users.
     *
     * @var array
     */
    protected $middlewares = [
        'web',
        'lit.auth:lit',
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
     * Get the authenticated litstack user.
     *
     * @return Authorizable|null
     */
    public function user()
    {
        return Auth::guard('lit')->user();
    }

    /**
     * Authorize litstack user.
     *
     * @param  Authorizable $user
     * @return bool
     */
    public function authozire(Authorizable $user): bool
    {
        if (config('app.env') == 'production' && $user->email == 'admin@admin.com') {
            return false;
        }

        return true;
    }
}
