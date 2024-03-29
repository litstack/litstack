<?php

namespace Ignite\Permissions\Composer;

use Illuminate\View\View;

class PermissionsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        app('lit.vue.app')
            ->prop('permissions', $this->getPermissions())
            ->prop('roles', app(config('permission.models.role'))->where('guard_name', config('lit.guard'))->get());
    }

    /**
     * Get unique permissions for authenticated user.
     *
     * @return array $permissions
     */
    protected function getPermissions()
    {
        return lit_user()->getAllPermissions()->pluck('name');
    }
}
