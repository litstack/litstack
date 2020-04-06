<?php

namespace Fjord\User;

use Fjord\Support\Facades\Package;
use Fjord\Support\Facades\FjordRoute;
use Fjord\User\Controllers\FjordUserController;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

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

    public function addNavPresets()
    {
        $this->package->addNavPreset('users', [
            'link' => route('fjord.aw-studio.fjord.users'),
            'title' => __f('fj.users'),
            'permission' => 'read fjord-users',
            'icon' => '<i class="fas fa-users">',
        ]);
    }

    public function map()
    {
        $this->mapUserRoleRoutes();
    }


    protected function mapUserRoleRoutes()
    {

        $this->package->route()->get('/fjord/users', FjordUserController::class . '@showIndex')
            ->name('users');

        $this->package->route()->post('/fjord/users-index', FjordUserController::class . '@fetchIndex')->name('users.index');
        $this->package->route()->post('/fjord/users/delete-all', FjordUserController::class . '@deleteAll')->name('users.delete');

        //FjordRoute::put('/user_roles', FjordUserController::class . '@update')->name('user_roles.update');
    }
}
