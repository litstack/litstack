<?php

namespace Ignite\Permissions\Composer;

use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PermissionsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        app('lit.vue.app')
            ->prop('permissions', $this->getPermissions())
            ->prop('roles', Role::all());
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
