<?php

namespace Fjord\Permissions\Composer;

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
        fjord()
            ->app()
            ->get('vue.app')
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
        return fjord_user()->getAllPermissions();
    }
}
