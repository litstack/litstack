<?php

namespace Fjord\Auth;

use Fjord\Support\Facades\FjordRoute;
use Fjord\Auth\Controllers\AuthController;
use Fjord\Auth\Controllers\ResetPasswordController;
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
        FjordRoute::public()
            ->get('login', AuthController::class . '@login')
            ->name('login');

        FjordRoute::public()
            ->post('login', AuthController::class . '@authenticate')
            ->name('login.post');

        FjordRoute::post('logout', AuthController::class . '@logout')
            ->name('logout');

        FjordRoute::post('logout/session', AuthController::class . '@logoutSession')
            ->name('logout.session');

        FjordRoute::post('/fjord/register', AuthController::class . '@register')
            ->name('register');

        FjordRoute::public()
            ->get('/fjord/password/reset/{token}', ResetPasswordController::class . '@showResetForm')->name('password.reset');
        FjordRoute::public()
            ->post('/fjord/password/reset', ResetPasswordController::class . '@reset')->name('password.request');
    }
}
