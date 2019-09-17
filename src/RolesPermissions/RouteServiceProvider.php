<?php
namespace AwStudio\Fjord\RolesPermissions;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use AwStudio\Fjord\RolesPermissions\Controllers\RolePermissionController;
use AwStudio\Fjord\RolesPermissions\Controllers\UserRoleController;

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
        FjordRoute::get('/role-permissions', RolePermissionController::class . '@index')
            ->name('role-permissions');
        FjordRoute::put('/role_permissions', RolePermissionController::class . '@update')->name('role_permissions.update');
    }

    protected function mapUserRoleRoutes()
    {
        FjordRoute::get('/user-roles', UserRoleController::class . '@index')
            ->name('user-roles');
        FjordRoute::put('/user_roles', UserRoleController::class . '@update')->name('user_roles.update');
    }
}
