<?php

namespace Fjord\Permissions\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return fjord_user()->can('update fjord-user-roles');
    }

    public function rules()
    {
        return [];
    }
}
