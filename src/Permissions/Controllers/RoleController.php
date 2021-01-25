<?php

namespace Ignite\Permissions\Controllers;

use Ignite\Permissions\Requests\Role\CreateRoleRequest;
use Ignite\Permissions\Requests\Role\DeleteRoleRequest;
use Ignite\Permissions\Requests\Role\UpdateRoleRequest;
use Ignite\Support\Facades\Lit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController
{
    /**
     * Find or fail the user by the given id.
     *
     * @param  int             $id
     * @return Authenticatable
     */
    protected function findOrFailUser($id): Authenticatable
    {
        $model = Lit::getUserModel();

        return $model::findOrFail($id);
    }

    /**
     * Assign role to lit-user.
     *
     * @param  Request $request
     * @param  User    $user_id
     * @return void
     */
    public function assignRoleToUser(UpdateRoleRequest $request, $user_id, $role_id)
    {
        $user = $this->findOrFailUser($user_id);

        $role = Role::findOrFail($role_id);

        $user->assignRole($role);
    }

    /**
     * Remove role to from lit-user.
     *
     * @param  Request $request
     * @param  User    $user_id
     * @return void
     */
    public function removeRoleFromUser(UpdateRoleRequest $request, $user_id, $role_id)
    {
        $user = $this->findOrFailUser($user_id);

        $role = $user->roles()->findOrFail($role_id);

        // Can't take away own admin role.
        if ($role->name == 'admin' && $user->id == lit_user()->id) {
            return response()->danger(__lit('permissions.messages.cant_remove_admin_role'));
        }

        // Remove role.
        $user->removeRole($role);

        // Apply user role if user has no roles.
        if ($user->roles()->count() == 0) {
            $user->assignRole(Role::where('name', 'user')->first());
        }
    }

    /**
     * Create new role.
     *
     * @param  CreateRoleRequest $request
     * @return Role
     */
    public function store(CreateRoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return $role;
    }

    /**
     * Delete role.
     *
     * @param  DeleteRoleRequest $request
     * @param  int               $id
     * @return void
     */
    public function destroy(DeleteRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        // Roles admin & user cannot be deletet.
        if (in_array($role->name, ['admin', 'user'])) {
            $roleName = __lit("roles.{$role->name}") !== "roles.{$role->name}"
                ? __lit("roles.{$role->name}")
                : ucfirst($role->name);
            abort(422, __lit('fjpermissions.cant_delete_role', ['role' => $roleName]));
        }

        // Users with the role to be deleted are assigned the role user.
        foreach ($role->users as $user) {
            if ($user->roles()->count() > 1) {
                continue;
            }

            $user->assignRole('user');
        }

        $role->delete();
    }
}
