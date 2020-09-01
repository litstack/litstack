<?php

namespace Ignite\Permissions\Requests\RolePermission;

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
        return lit_user()->can('update lit-role-permissions');
    }

    public function rules()
    {
        return [];
    }
}
