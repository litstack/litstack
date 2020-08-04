<?php

namespace Fjord\Permissions\Requests\Role;

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
        return fjord_user()->can('create fjord-user-roles');
    }

    public function rules()
    {
        return [];
    }
}
