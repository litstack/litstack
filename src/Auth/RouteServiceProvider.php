<?php

namespace Ignite\Auth;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Ignite\Auth\Controllers\AuthController;
use Ignite\Auth\Controllers\ResetPasswordController;
use Ignite\Support\Facades\Route;

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
        Route::public()
            ->get('login', AuthController::class.'@login')
            ->name('login');

        Route::public()
            ->post('login', AuthController::class.'@authenticate')
            ->name('login.post');

        Route::post('logout', AuthController::class.'@logout')
            ->name('logout');

        Route::post('logout/session', AuthController::class.'@logoutSession')
            ->name('logout.session');

        Route::post('/lit/register', AuthController::class.'@register')
            ->name('register');

        Route::public()
            ->get('/lit/password/reset/{token}', ResetPasswordController::class.'@showResetForm')->name('password.reset');
        Route::public()
            ->post('/lit/password/reset', ResetPasswordController::class.'@reset')->name('password.request');
    }
}
