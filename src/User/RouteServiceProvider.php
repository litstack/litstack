<?php

namespace AwStudio\Fjord\User;

use AwStudio\Fjord\Support\Facades\Package;
use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\User\Controllers\FjordUserController;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{

    public function boot()
    {

        parent::boot();
    }

    public function map()
    {
        $this->mapUserRoleRoutes();
    }


    protected function mapUserRoleRoutes()
    {
        $package = Package::get('aw-studio/fjord');

        $package->route()->get('/fjord/users', FjordUserController::class . '@showIndex')
            ->name('users');

        $package->route()->post('/fjord/users-index', FjordUserController::class . '@fetchIndex')->name('users.index');
        $package->route()->post('/fjord/users/delete-all', FjordUserController::class . '@deleteAll')->name('users.delete');

        //FjordRoute::put('/user_roles', FjordUserController::class . '@update')->name('user_roles.update');
    }
}
