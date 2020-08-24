<?php

namespace Lit\User;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Lit\Support\Facades\Package;
use Lit\User\Controllers\LitUserController;
use Lit\User\Controllers\ProfileController;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->package = Package::get('litstack/litstack');

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
        $this->package->route()->get('profile-sessions', ProfileController::class.'@sessions')->name('sessions');
        $this->package->route()->get('/lit/users', LitUserController::class.'@showIndex')->name('users');
        $this->package->route()->post('/lit/users-index', LitUserController::class.'@fetchIndex')->name('users.index');
        $this->package->route()->post('/lit/users/delete-all', LitUserController::class.'@deleteAll')->name('users.delete');
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

        $this->package->addNavPreset('profile', [
            'link' => function () use ($config) {
                $id = app()->runningInConsole()
                    ? '{user_id}'
                    : lit_user()->id;

                return lit()->url(
                    "$config->route_prefix/{$id}"
                );
            },
            'title' => fn () => __f('lit.profile'),
            'icon'  => fa('user-cog'),
        ]);
    }
}
