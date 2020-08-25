<?php

namespace Lit\Permissions;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Lit\Permissions\Controllers\PermissionController;
use Lit\Permissions\Controllers\RoleController;
use Lit\Permissions\Controllers\RolePermissionController;
use Lit\Support\Facades\Package;

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
            'link'      => route('lit.permissions'),
            'title'     => fn ()     => __lit('lit.permissions'),
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
        $route = $this->package->route()
            ->get('/permissions', PermissionController::class.'@index')
            ->name('permissions');

        $this->package->route()
            ->post('/lit-user/{user_id}/role/{role_id}', RoleController::class.'@assignRoleToUser')
            ->name('role.assign');

        $this->package->route()
            ->delete('/lit-user/{user_id}/role/{role_id}', RoleController::class.'@removeRoleFromUser')
            ->name('role.remove');

        $this->package->route()
            ->post('/role', RoleController::class.'@store')
            ->name('role.store');

        $this->package->route()
            ->delete('/role/{id}', RoleController::class.'@destroy')
            ->name('role.destroy');

        $this->package->route()
            ->post('permissions/index', PermissionController::class.'@fetchIndex')
            ->name('permissions.index');

        $this->package->route()
            ->put('/role_permissions', RolePermissionController::class.'@update')
            ->name('role_permissions.update');
    }
}
