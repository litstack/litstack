<?php

namespace Lit\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LitSessionLogoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return lit_user()->sessions()->exists($request->id);
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
