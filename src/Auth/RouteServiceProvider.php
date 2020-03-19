<?php

namespace AwStudio\Fjord\Auth;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\Auth\Controllers\AuthController;
use AwStudio\Fjord\Auth\Controllers\ResetPasswordController;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapAuthRoutes();
    }

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

        FjordRoute::post('/fjord/register', AuthController::class . '@register');

        FjordRoute::public()
            ->get('/fjord/password/reset/{token}', ResetPasswordController::class . '@showResetForm')->name('password.reset');
        FjordRoute::public()
            ->post('/fjord/password/reset', ResetPasswordController::class . '@reset')->name('password.request');
    }
}
