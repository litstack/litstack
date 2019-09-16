<?php

namespace AwStudio\Fjord\Form\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrudCreateRequest extends FormRequest
{
    use Traits\ModelName;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        return auth()->user()->can('create ' . $this->model());
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
