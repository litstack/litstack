<?php

namespace AwStudio\Fjord\RolesPermissions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRolePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return fjord_user()->can('read role-permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
