<?php

namespace Ignite\Auth;

use Ignite\Auth\Controllers\AuthController;
use Ignite\Auth\Controllers\ResetPasswordController;
use Ignite\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
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
        Route::public()->get('password/reset/{token}', ResetPasswordController::class.'@showResetForm')->name('password.reset');
        Route::public()->post('password/reset', ResetPasswordController::class.'@request')->name('password.request');

        Route::public()->get('login', AuthController::class.'@login')->name('login');
        Route::public()->post('login', AuthController::class.'@authenticate')->name('login.post');
        Route::post('logout', AuthController::class.'@logout')->name('logout');
    }
}
