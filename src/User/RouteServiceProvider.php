<?php

namespace Ignite\User;

use Ignite\Support\Facades\Nav;
use Ignite\Support\Facades\Route as LitstackRoute;
use Ignite\User\Controllers\ProfileController;
use Ignite\User\Controllers\UserController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $provider = $this;

        $this->app->booted(function () use ($provider) {
            $provider->addNavPresets();
        });
    }

    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        $this->mapUserRoleRoutes();
    }

    /**
     * Map user role routes.
     *
     * @return void
     */
    protected function mapUserRoleRoutes()
    {
        LitstackRoute::get('profile-sessions', ProfileController::class.'@sessions')->name('sessions');
        LitstackRoute::get('/lit/users', UserController::class.'@showIndex')->name('users');
        LitstackRoute::post('/lit/users-index', UserController::class.'@fetchIndex')->name('users.index');
        LitstackRoute::post('/lit/users/delete-all', UserController::class.'@deleteAll')->name('users.delete');
    }

    /**
     * Add nav presets.
     *
     * @return void
     */
    public function addNavPresets()
    {
        if (! $config = lit()->config('user.profile_settings')) {
            return;
        }

        Nav::preset('profile', [
            'link' => function () use ($config) {
                $id = app()->runningInConsole()
                    ? '{user_id}'
                    : lit_user()->id;

                return lit()->url(
                    "$config->route_prefix/{$id}"
                );
            },
            'title' => fn () => __lit('lit.profile'),
            'icon'  => fa('user-cog'),
        ]);
    }
}
