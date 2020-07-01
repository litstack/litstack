<?php

namespace Fjord\User;

use Fjord\Support\Facades\Package;
use Fjord\User\Controllers\FjordUserController;
use Fjord\User\Controllers\ProfileController;
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
        $this->package = Package::get('aw-studio/fjord');

        parent::boot();
        $provider = $this;
        $this->app->booted(function () use ($provider) {
            $provider->addNavPresets();
        });
    }

    /**
     * Add nav presets.
     *
     * @return void
     */
    public function addNavPresets()
    {
        if (! $config = fjord()->config('user.profile_settings')) {
            return;
        }

        $this->package->addNavPreset('profile', [
            'link' => function () use ($config) {
                $id = app()->runningInConsole()
                    ? '{user_id}'
                    : fjord_user()->id;

                return fjord()->url(
                    "$config->route_prefix/{$id}"
                );
            },
            'title' => fn () => __f('fj.profile'),
            'icon'  => fa('user-cog'),
        ]);
    }

    public function map()
    {
        $this->mapUserRoleRoutes();
    }

    protected function mapUserRoleRoutes()
    {
        $this->package->route()->get('profile-sessions', ProfileController::class.'@sessions')->name('sessions');
        $this->package->route()->get('/fjord/users', FjordUserController::class.'@showIndex')->name('users');
        $this->package->route()->post('/fjord/users-index', FjordUserController::class.'@fetchIndex')->name('users.index');
        $this->package->route()->post('/fjord/users/delete-all', FjordUserController::class.'@deleteAll')->name('users.delete');
    }
}
