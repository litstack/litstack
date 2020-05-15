<?php

namespace Fjord\Auth\Requests;

use Illuminate\Http\Request;
use Fjord\Auth\Models\FjordSession;
use Illuminate\Foundation\Http\FormRequest;

class FjordSessionLogoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return fjord_user()->sessions()->exists($request->id);
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
