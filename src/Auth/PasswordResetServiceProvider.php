<?php

namespace Ignite\Auth;

use Ignite\Auth\Controllers\ResetPasswordController;
use Ignite\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class PasswordResetServiceProvider extends RouteServiceProvider
{
    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        // Forgot password.
        Route::guest()->get('password/forgot', ResetPasswordController::class.'@showForgotForm')->name('password.forgot.show');
        Route::guest()->post('password/forgot', ResetPasswordController::class.'@forgot')->name('password.forgot.store');

        // Reset password.
        Route::guest()->get('password/reset/{token}', ResetPasswordController::class.'@showResetForm')->name('password.reset.show');
        Route::guest()->post('password/reset', ResetPasswordController::class.'@reset')->name('password.reset.store');
    }
}
