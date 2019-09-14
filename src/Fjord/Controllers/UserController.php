<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use AwStudio\Fjord\Fjord\Models\RolePermission;

class UserController extends Controller
{
    public function index()
    {
        // TODO: Role/Permission Table
        return view('fjord::vue')->withComponent('roles-permissions')
            ->withTitle('Users')
            ->withProps([
                'roles' => Role::all(),
                'permissions' => Permission::all(),
                'role_permissions' => RolePermission::all()
            ]);
    }
}
