<?php

namespace Ignite\Crud\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CrudUpdateRequest extends FormRequest
{
    use Traits\AuthorizeController;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return $this->authorizeController($request, 'update');
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
