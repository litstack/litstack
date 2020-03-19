<?php

namespace AwStudio\Fjord\RolesPermissions;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use AwStudio\Fjord\RolesPermissions\Controllers\FjordPermissionController;
use AwStudio\Fjord\RolesPermissions\Controllers\FjordUserController;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapRolePermissionRoutes();
        $this->mapUserRoleRoutes();
    }

    protected function mapRolePermissionRoutes()
    {
        FjordRoute::get('/fjord/permissions', FjordPermissionController::class . '@index')
            ->name('permissions');
        FjordRoute::put('/role_permissions', FjordPermissionController::class . '@update')->name('role_permissions.update');
    }

    protected function mapUserRoleRoutes()
    {
        FjordRoute::get('/fjord/users', FjordUserController::class . '@index')
            ->name('users');
        FjordRoute::put('/user_roles', FjordUserController::class . '@update')->name('user_roles.update');
    }
}
