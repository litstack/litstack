<?php

namespace AwStudio\Fjord\Form\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CrudDeleteRequest extends FormRequest
{
    use Traits\ModelName,
        Traits\HasPermissions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        if($this->hasPermissions($request)){
            return auth()->user()->can('delete ' . $this->model());
        }else{
            return true;
        }
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
