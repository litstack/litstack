<?php

namespace Fjord\Permissions\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return fjord_user()->can('update fjord-role-permissions');
    }

    public function rules()
    {
        return [];
    }
}
