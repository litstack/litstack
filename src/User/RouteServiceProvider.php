<?php

namespace Fjord\User;

use Fjord\Support\Facades\Crud;
use Fjord\Support\Facades\Config;
use Fjord\Support\Facades\Package;
use Fjord\Support\Facades\FjordRoute;
use Illuminate\Support\Facades\Route;
use Fjord\User\Controllers\ProfileController;
use Fjord\User\Controllers\FjordUserController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{

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
     * Add nav presets
     *
     * @return void
     */
    public function addNavPresets()
    {
        if (!$config = fjord()->config('user.profile_settings')) {
            return;
        }
        $this->package->addNavPreset('profile', [
            'link' => fn () => fjord()->url("$config->route_prefix/" . fjord_user()->id),
            'title' => __f('fj.profile'),
            'icon' => fa('user-cog'),
        ]);
    }

    public function map()
    {
        $this->mapUserRoleRoutes();
    }

    protected function mapUserRoleRoutes()
    {
        $this->package->route()->group(function () {
            Route::group([
                'config' => 'user.profile_settings',
                'prefix' => '/profile',
                'as' => 'profile.'
            ], function () {
                Route::get('/sessions', ProfileController::class . '@sessions')->name('sessions');
            });
        });

        $this->package->route()->get('/fjord/users', FjordUserController::class . '@showIndex')->name('users');
        $this->package->route()->post('/fjord/users-index', FjordUserController::class . '@fetchIndex')->name('users.index');
        $this->package->route()->post('/fjord/users/delete-all', FjordUserController::class . '@deleteAll')->name('users.delete');
    }
}
