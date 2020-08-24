<?php

namespace Lit\Auth;

use Lit\Auth\Controllers\AuthController;
use Lit\Auth\Controllers\ResetPasswordController;
use Lit\Support\Facades\LitRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAuthRoutes();
    }

    /**
     * Map auth routes.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        LitRoute::public()
            ->get('login', AuthController::class.'@login')
            ->name('login');

        LitRoute::public()
            ->post('login', AuthController::class.'@authenticate')
            ->name('login.post');

        LitRoute::post('logout', AuthController::class.'@logout')
            ->name('logout');

        LitRoute::post('logout/session', AuthController::class.'@logoutSession')
            ->name('logout.session');

        LitRoute::post('/lit/register', AuthController::class.'@register')
            ->name('register');

        LitRoute::public()
            ->get('/lit/password/reset/{token}', ResetPasswordController::class.'@showResetForm')->name('password.reset');
        LitRoute::public()
            ->post('/lit/password/reset', ResetPasswordController::class.'@reset')->name('password.request');
    }
}
