<?php

namespace Ignite\Permissions\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return lit_user()->can('create lit-user-roles');
    }

    public function rules()
    {
        return [];
    }
}
