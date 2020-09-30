<?php

namespace Ignite\Permissions;

use Ignite\Permissions\Controllers\PermissionController;
use Ignite\Permissions\Controllers\RoleController;
use Ignite\Permissions\Controllers\RolePermissionController;
use Ignite\Support\Facades\Nav;
use Ignite\Support\Facades\Route as LitstackRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Boot application services.
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
        $this->mapRolePermissionRoutes();
    }

    /**
     * Add [permissions] nav preset.
     *
     * @return void
     */
    protected function addNavPresets()
    {
        $this->app['lit.nav']->preset('permissions', [
            'link'      => route('lit.permissions'),
            'title'     => fn () => ucfirst(__lit('base.permissions')),
            'icon'      => fa('unlock-alt'),
            'authorize' => function ($user) {
                return $user->can('read lit-role-permissions');
            },
        ]);
    }

    /**
     * Map role/permissions routes.
     *
     * @return void
     */
    protected function mapRolePermissionRoutes()
    {
        LitstackRoute::get('/permissions', PermissionController::class.'@index')
            ->name('permissions');

        LitstackRoute::post('/lit-user/{user_id}/role/{role_id}', RoleController::class.'@assignRoleToUser')
            ->name('role.assign');

        LitstackRoute::delete('/lit-user/{user_id}/role/{role_id}', RoleController::class.'@removeRoleFromUser')
            ->name('role.remove');

        LitstackRoute::post('/role', RoleController::class.'@store')
            ->name('role.store');

        LitstackRoute::delete('/role/{id}', RoleController::class.'@destroy')
            ->name('role.destroy');

        LitstackRoute::post('permissions/index', PermissionController::class.'@fetchIndex')
            ->name('permissions.index');

        LitstackRoute::put('/role_permissions', RolePermissionController::class.'@update')
            ->name('role_permissions.update');
    }
}
