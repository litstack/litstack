<?php

namespace Fjord\Permissions;

use Fjord\Permissions\Controllers\PermissionController;
use Fjord\Permissions\Controllers\RoleController;
use Fjord\Permissions\Controllers\RolePermissionController;
use Fjord\Support\Facades\Package;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Package instance.
     *
     * @var mixed
     */
    protected $package;

    /**
     * Boot application services.
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
        $this->package->addNavPreset('permissions', [
            'link'      => route('fjord.aw-studio.fjord.permissions'),
            'title'     => fn ()     => __f('fj.permissions'),
            'icon'      => fa('unlock-alt'),
            'authorize' => function ($user) {
                return $user->can('read fjord-role-permissions');
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
        $route = $this->package->route()
            ->get('/permissions', PermissionController::class.'@index')
            ->name('permissions');

        $this->package->route()
            ->post('/fjord-user/{user_id}/role/{role_id}', RoleController::class.'@assignRoleToUser')
            ->name('role.assign');

        $this->package->route()
            ->delete('/fjord-user/{user_id}/role/{role_id}', RoleController::class.'@removeRoleFromUser')
            ->name('role.remove');

        $this->package->route()
            ->post('/role', RoleController::class.'@store')
            ->name('role.store');

        $this->package->route()
            ->delete('/role/{id}', RoleController::class.'@destroy')
            ->name('role.destroy');

        $this->package->route()
            ->post('/index', PermissionController::class.'@fetchIndex')
            ->name('permissions.index');

        $this->package->route()
            ->put('/role_permissions', RolePermissionController::class.'@update')
            ->name('role_permissions.update');
    }
}
